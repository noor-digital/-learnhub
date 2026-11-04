<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function generate(Course $course)
    {
        $user = auth()->user();

        // Vérification 1 : inscrit au cours
        abort_unless($user->isEnrolledIn($course), 403, 'Inscription requise.');

        // Vérification 2 : progression 100%
        $enrollment = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->first();

        $progress = $enrollment?->pivot->progress ?? 0;

        abort_unless(
            $progress >= 100,
            403,
            'Terminez le cours à 100% pour obtenir le certificat.'
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