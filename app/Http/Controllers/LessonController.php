<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    use AuthorizesRequests;

    // ─────────────────────────────────────────────
    // GET /courses/{course}/lessons/create
    // ─────────────────────────────────────────────
    public function create(Course $course): View
    {
        // Vérifie manuellement : admin OU propriétaire du cours
        if (! auth()->user()->isAdmin() && $course->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('lessons.create', compact('course'));
    }

    // ─────────────────────────────────────────────
    // POST /courses/{course}/lessons
    // ─────────────────────────────────────────────
  public function store(Request $request, Course $course): RedirectResponse
{
    if (! auth()->user()->isAdmin() && $course->user_id !== auth()->id()) {
        abort(403, 'Action non autorisée.');
    }

    $validated = $request->validate([
        'title'     => ['required', 'string', 'max:255'],
        'video_url' => ['nullable', 'url', 'max:500'],
        'duration'  => ['nullable', 'integer', 'min:0', 'max:999'],
        'order'     => ['nullable', 'integer', 'min:1'],
    ]);

    // ✅ Si order est vide, place la leçon à la fin automatiquement
    if (empty($validated['order'])) {
        $validated['order'] = ($course->lessons()->max('order') ?? 0) + 1;
    }

    $validated['course_id'] = $course->id;

    Lesson::create($validated);

    return redirect()
        ->route('courses.show', $course)
        ->with('success', 'Leçon "' . $validated['title'] . '" ajoutée !');
}

    // ─────────────────────────────────────────────
    // GET /courses/{course}/lessons/{lesson}
    // ─────────────────────────────────────────────
    public function show(Course $course, Lesson $lesson): View
    {
        abort_if($lesson->course_id !== $course->id, 404);

        $user = auth()->user();

        // Accès délégué à Course::isAccessibleBy() :
        //  - admin                                  → accès
        //  - formateur propriétaire du cours        → accès
        //  - cours gratuit + inscription            → accès
        //  - cours payant + payment_status = 'paid' → accès
        //  - sinon (non inscrit / payant non payé)  → 403
        abort_unless(
            $course->isAccessibleBy($user),
            403,
            'Inscription requise : vous devez être inscrit (et avoir payé pour un cours payant) pour accéder à cette leçon.'
        );

        $lessons = $course->lessons()->orderBy('order')->get();

        $completedIds = $user->completedLessons()
            ->pluck('lessons.id')
            ->toArray();

        return view('lessons.show', compact('course', 'lesson', 'lessons', 'completedIds'));
    }

    // ─────────────────────────────────────────────
    // GET /courses/{course}/lessons/{lesson}/edit
    // ─────────────────────────────────────────────
    public function edit(Course $course, Lesson $lesson): View
    {
        abort_if($lesson->course_id !== $course->id, 404);

        // Vérifie manuellement : admin OU propriétaire du cours
        if (! auth()->user()->isAdmin() && $course->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('lessons.edit', compact('course', 'lesson'));
    }

    // ─────────────────────────────────────────────
    // PUT /courses/{course}/lessons/{lesson}
    // ─────────────────────────────────────────────
    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
{
    abort_if($lesson->course_id !== $course->id, 404);

    if (! auth()->user()->isAdmin() && $course->user_id !== auth()->id()) {
        abort(403, 'Action non autorisée.');
    }

    $validated = $request->validate([
        'title'     => ['required', 'string', 'max:255'],
        'video_url' => ['nullable', 'url', 'max:500'],
        'duration'  => ['nullable', 'integer', 'min:0', 'max:999'],
        'order'     => ['nullable', 'integer', 'min:1'],
    ]);

    // ✅ Si order est vide, on garde l'ordre actuel de la leçon
    if (empty($validated['order'])) {
        $validated['order'] = $lesson->order;
    }

    $lesson->update($validated);

    return redirect()
        ->route('courses.show', $course)
        ->with('success', 'Leçon "' . $lesson->title . '" mise à jour !');
}
    // ─────────────────────────────────────────────
    // DELETE /courses/{course}/lessons/{lesson}
    // ─────────────────────────────────────────────
    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        abort_if($lesson->course_id !== $course->id, 404);

        // Vérifie manuellement : admin OU propriétaire du cours
        if (! auth()->user()->isAdmin() && $course->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $title = $lesson->title;
        $lesson->delete();

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Leçon "' . $title . '" supprimée.');
    }

    // ─────────────────────────────────────────────
    // POST /lessons/{lesson}/complete
    // ─────────────────────────────────────────────
    public function markComplete(Lesson $lesson): RedirectResponse
    {
        $user   = auth()->user();
        $course = $lesson->course;

        abort_unless(
            $user->isEtudiant() && $course->isAccessibleBy($user),
            403,
            'Seuls les étudiants ayant une inscription active peuvent modifier leur progression.'
        );

        if ($lesson->isCompletedBy($user)) {
            // detach() supprime toutes les lignes pivot de cet utilisateur
            // pour cette leçon (y compris d'éventuels doublons existants).
            $lesson->completedByUsers()->detach($user->id);
            $message = 'Leçon marquée comme non terminée.';
        } else {
            // syncWithoutDetaching() n'insère la ligne pivot que si elle
            // n'existe pas déjà : aucun doublon, aucune autre ligne supprimée.
            $lesson->completedByUsers()->syncWithoutDetaching([
                $user->id => ['completed_at' => now()],
            ]);
            $message = '✅ Leçon terminée !';
        }

        // La progression est recalculée dans les DEUX cas
        // (leçon terminée ou marquée comme non terminée).
        $this->recalculateProgress($user, $course);

        return back()->with('success', $message);
    }

    // ─────────────────────────────────────────────
    // Recalcule la progression de l'étudiant
    // ─────────────────────────────────────────────
    private function recalculateProgress($user, Course $course): void
    {
        $total = $course->lessons()->count();

        if ($total > 0) {
            // distinct() : si des doublons existent déjà dans la table pivot,
            // une même leçon n'est comptée qu'une seule fois.
            $completed = $user->completedLessons()
                              ->where('lessons.course_id', $course->id)
                              ->distinct()
                              ->count('lessons.id');

            // min(100, …) : sécurité pour ne jamais dépasser 100 %.
            $progress = min(100, intdiv($completed * 100, $total));
        } else {
            // Cours sans leçon : on force la progression à 0 %
            // au lieu de conserver une ancienne valeur.
            $progress = 0;
        }

        $user->enrolledCourses()
             ->updateExistingPivot($course->id, ['progress' => $progress]);
    }
}