<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        abort_unless($user->isEtudiant() || $user->isAdmin(), 403);

        // Cours inscrits avec progression et relations
        $enrolledCourses = $user->enrolledCourses()
            ->with(['category', 'formateur', 'lessons'])
            ->withCount('lessons')
            ->get()
            ->map(function ($course) {
                $course->progress    = $course->pivot->progress ?? 0;
                $course->enrolled_at = $course->pivot->enrolled_at;
                return $course;
            });

        // Statistiques globales
        $stats = [
            'total_enrolled'   => $enrolledCourses->count(),
            'completed'        => $enrolledCourses->where('progress', 100)->count(),
            'in_progress'      => $enrolledCourses->whereBetween('progress', [1, 99])->count(),
            'avg_progress'     => $enrolledCourses->isNotEmpty()
                                    ? round($enrolledCourses->avg('progress'))
                                    : 0,
        ];

        // Dernières leçons complétées (activité récente)
        $recentActivity = $user->completedLessons()
            ->with('course')
            ->orderByPivot('completed_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.student', compact(
            'enrolledCourses',
            'stats',
            'recentActivity'
        ));
    }
}