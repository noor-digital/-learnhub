<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ─────────────────────────────────────────────────
    // store() — Déposer un avis
    // POST /courses/{course}/reviews
    // ─────────────────────────────────────────────────
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = auth()->user();

        // Seuls les étudiants ayant un accès actif à un cours publié
        // peuvent laisser un avis.
        if (! $user->isEtudiant() || ! $course->isAccessibleBy($user)) {
            return back()->with('error', 'Une inscription active à un cours publié est nécessaire pour laisser un avis.');
        }

        // Vérification : pas déjà un avis
        $exists = Review::where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà laissé un avis sur ce cours.');
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'rating'    => $validated['rating'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return back()->with('success', '⭐ Merci pour votre avis !');
    }

    // ─────────────────────────────────────────────────
    // destroy() — Supprimer son avis
    // DELETE /reviews/{review}
    // ─────────────────────────────────────────────────
    public function destroy(Review $review): RedirectResponse
    {
        $user = auth()->user();

        // Seulement l'auteur de l'avis ou un admin peut le supprimer
        if ((int) $review->user_id !== (int) $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $courseId = $review->course_id;
        $review->delete();

        return redirect()
            ->route('courses.show', $courseId)
            ->with('success', 'Avis supprimé.');
    }
}