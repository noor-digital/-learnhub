<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function generate(Course $course)
    {
        $user = auth()->user();

        // Un certificat est réservé à un étudiant disposant d'une
        // inscription gratuite ou payée sur un cours publié.
        abort_unless(
            $user->isEtudiant() && $course->isAccessibleBy($user),
            403,
            'Une inscription active est requise pour obtenir le certificat.'
        );

        // Vérifie la progression réelle, sans se fier uniquement au pourcentage
        // stocké (qui peut être ancien ou arrondi à 100 % trop tôt).
        $totalLessons = $course->lessons()->count();
        $completedLessons = $user->completedLessons()
            ->where('lessons.course_id', $course->id)
            ->distinct()
            ->count('lessons.id');

        abort_unless(
            $totalLessons > 0 && $completedLessons === $totalLessons,
            403,
            'Terminez toutes les leçons du cours pour obtenir le certificat.'
        );

        $course->load(['formateur', 'category']);

        $data = [
            'user'           => $user,
            'course'         => $course,
            'date'           => now()->translatedFormat('d F Y'),
            'certificate_id' => strtoupper(
                substr(md5($user->id . $course->id . 'LMS2025'), 0, 12)
            ),
            'issued_at'      => now()->format('Y'),
        ];

        $pdf = app(\Barryvdh\DomPDF\PDF::class)
            ->loadView('certificates.template', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download(
            'certificat-' . Str::slug($course->title) . '.pdf'
        );
    }
}