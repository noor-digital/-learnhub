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

        // Déjà inscrit ? On redirige vers le cours avec un message adapté
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            return $this->redirectAlreadyEnrolled($course, $enrollment);
        }

        // Cours gratuit : l'inscription passe par la route d'inscription
        // gratuite, qui est la seule à créer l'inscription.
        if ($this->isFree($course)) {
            return redirect()->route('enrollments.store', $course);
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

        // Un cours gratuit ne passe jamais par le paiement simulé
        if ($this->isFree($course)) {
            return redirect()
                ->route('courses.show', $course)
                ->with('error', 'Ce cours est gratuit : veuillez utiliser l\'inscription gratuite.');
        }

        // Re-vérification de l'inscription au moment du traitement, dans une
        // transaction verrouillant la ligne du cours pour limiter les doublons
        // en cas de double soumission simultanée.
        $existing = DB::transaction(function () use ($user, $course) {
            Course::whereKey($course->id)->lockForUpdate()->first();

            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($enrollment) {
                return $enrollment;
            }

            // Paiement SIMULÉ : l'inscription est créée avec le statut
            // 'paid' uniquement dans le cadre de cette simulation.
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
        $expectedStatus = $this->isFree($course) ? 'free' : 'paid';

        $message = $enrollment->payment_status === $expectedStatus
            ? 'Vous êtes déjà inscrit à ce cours.'
            : 'Une inscription existe déjà pour ce cours : aucun nouveau paiement ne peut être effectué depuis cette page.';

        return redirect()
            ->route('courses.show', $course)
            ->with('info', $message);
    }
}