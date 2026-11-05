<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // ─────────────────────────────────────────────────
    // Page de paiement SIMULÉ
    // GET /courses/{course}/payment
    //
    // Cette méthode est en lecture seule : elle ne crée
    // jamais d'inscription.
    // ─────────────────────────────────────────────────
    public function show(Course $course): View|RedirectResponse
    {
        $user = auth()->user();

        // Seuls les étudiants peuvent acheter un cours
        abort_unless(
            $user && $user->isEtudiant(),
            403,
            'Seuls les étudiants peuvent acheter un cours.'
        );

        abort_unless($course->is_published, 404);

        // Une inscription active ne doit pas repasser par le paiement.
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment && in_array($enrollment->payment_status, ['free', 'paid'], true)) {
            return $this->redirectAlreadyEnrolled($course, $enrollment);
        }

        // Un cours gratuit doit être rejoint depuis son formulaire d'inscription.
        if ($this->isFree($course)) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'Ce cours est gratuit : utilisez le bouton d’inscription.');
        }

        return view('payment.show', compact('course'));
    }

    // ─────────────────────────────────────────────────
    // Traitement du paiement SIMULÉ
    // POST /courses/{course}/payment
    //
    // ⚠ SIMULATION : aucun prestataire de paiement n'est
    // contacté et aucun paiement réel n'est vérifié.
    // ─────────────────────────────────────────────────
    public function process(Course $course): RedirectResponse
    {
        $user = auth()->user();

        // Vérification côté serveur : étudiant uniquement
        abort_unless(
            $user && $user->isEtudiant(),
            403,
            'Seuls les étudiants peuvent acheter un cours.'
        );

        abort_unless($course->is_published, 404);

        // Un cours gratuit ne passe jamais par le paiement simulé.
        if ($this->isFree($course)) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'Ce cours est gratuit : utilisez le bouton d’inscription.');
        }

        // Verrouille le cours pour éviter les inscriptions concurrentes.
        // Une inscription pending est activée sans perdre sa progression.
        $existing = DB::transaction(function () use ($user, $course) {
            Course::whereKey($course->id)->lockForUpdate()->first();

            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->lockForUpdate()
                ->first();

            if ($enrollment) {
                if (in_array($enrollment->payment_status, ['free', 'paid'], true)) {
                    return $enrollment;
                }

                $enrollment->update(['payment_status' => 'paid']);

                return null;
            }

            // Paiement SIMULÉ : aucun prestataire réel n'est contacté.
            Enrollment::create([
                'user_id'        => $user->id,
                'course_id'      => $course->id,
                'enrolled_at'    => now(),
                'progress'       => 0,
                'payment_status' => 'paid',
            ]);

            return null;
        });

        if ($existing) {
            return $this->redirectAlreadyEnrolled($course, $existing);
        }

        return redirect()
            ->route('courses.show', $course)
            ->with(
                'success',
                '🎉 Paiement simulé enregistré (mode démonstration) : aucun paiement réel n\'a été effectué. Bonne formation !'
            );
    }

    // ─────────────────────────────────────────────────
    // Helpers privés
    // ─────────────────────────────────────────────────

    /**
     * Un cours est gratuit si son prix est nul (ou vide).
     */
    private function isFree(Course $course): bool
    {
        return (float) $course->price <= 0;
    }

    /**
     * Redirige vers le cours avec un message selon le statut de paiement
     * de l'inscription existante. L'inscription existante n'est jamais modifiée.
     */
    private function redirectAlreadyEnrolled(Course $course, Enrollment $enrollment): RedirectResponse
    {
        $message = in_array($enrollment->payment_status, ['free', 'paid'], true)
            ? 'Vous êtes déjà inscrit à ce cours.'
            : 'Une inscription est en attente : aucun accès n’est accordé avant sa régularisation.';

        return redirect()
            ->route('courses.show', $course)
            ->with('info', $message);
    }
}