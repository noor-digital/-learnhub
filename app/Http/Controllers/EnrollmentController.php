<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;

class EnrollmentController extends Controller
{
    // ─────────────────────────────────────────────────
    // store() — Inscrire l'étudiant au cours
    // POST /courses/{course}/enroll
    // ─────────────────────────────────────────────────
    public function store(Course $course): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isEtudiant(), 403, 'Seuls les étudiants peuvent s\'inscrire.');

        if ($user->isEnrolledIn($course)) {
            return back()->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        // Si le cours est payant, rediriger vers la page de paiement
        // (aucune inscription n'est créée avant le traitement du paiement)
        if ($course->price > 0) {
            return redirect()->route('payment.show', $course);
        }

        // Cours gratuit → inscription directe
        Enrollment::create([
            'user_id'        => $user->id,
            'course_id'      => $course->id,
            'enrolled_at'    => now(),
            'progress'       => 0,
            'payment_status' => 'free',
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', '🎉 Inscription réussie !');
    }

    // ─────────────────────────────────────────────────
    // destroy() — Désinscrire l'étudiant
    // DELETE /courses/{course}/enroll
    // ─────────────────────────────────────────────────
    public function destroy(Course $course): RedirectResponse
    {
        $user = auth()->user();

        // Seuls les étudiants peuvent se désinscrire
        abort_unless($user->isEtudiant(), 403, 'Seuls les étudiants peuvent se désinscrire.');

        // La recherche est limitée à l'utilisateur connecté : l'inscription
        // d'un autre utilisateur ne peut jamais être retrouvée ni supprimée.
        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->firstOrFail();

        // Vérification explicite de propriété avant suppression (défense en profondeur)
        abort_unless((int) $enrollment->user_id === (int) $user->id, 403);

        $enrollment->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Vous avez été désinscrit du cours.');
    }
}