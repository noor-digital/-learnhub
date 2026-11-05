<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FormateurDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'formateur']),
            403
        );

        $courses = Course::query()
            ->where('user_id', $user->id)
            ->withCount(['students', 'lessons', 'reviews'])
            ->withCount([
                'students as paid_students_count' => fn ($query) =>
                    $query->where('enrollments.payment_status', 'paid'),
            ])
            ->withAvg('reviews', 'rating')
            ->with('category')
            ->latest()
            ->get();

        // KPIs — variables directes, pas de tableau imbriqué
        $totalCourses  = $courses->count();
        $published     = $courses->where('is_published', true)->count();
        $drafts        = $courses->where('is_published', false)->count();
        $totalStudents = $courses->sum('students_count');
        $totalLessons  = $courses->sum('lessons_count');
        $revenueEst    = $courses->sum(
            fn($c) => $c->price * $c->students_count
        );
        $avgRating     = round(
            $courses->whereNotNull('reviews_avg_rating')
                    ->avg('reviews_avg_rating') ?? 0,
            1
        );

        // Top 5 par inscrits → graphique
        $chartLabels   = $courses->sortByDesc('students_count')
            ->take(5)->pluck('title')
            ->map(fn($t) => \Str::limit($t, 20))->values();

        $chartStudents = $courses->sortByDesc('students_count')
            ->take(5)->pluck('students_count')->values();

        $chartRevenues = $courses->sortByDesc('students_count')
            ->take(5)
            ->map(fn($c) => round((float) $c->price * $c->paid_students_count))
            ->values();

        // Activité récente simulée (inscriptions récentes)
        $recentActivity = collect();
        foreach ($courses->take(5) as $course) {
            if ($course->students_count > 0) {
                $recentActivity->push([
                    'icon'  => 'people',
                    'color' => '#0f6e56',
                    'bg'    => '#e1f5ee',
                    'text'  => $course->students_count . ' étudiant(s) inscrit(s)',
                    'sub'   => $course->title,
                    'time'  => $course->updated_at->diffForHumans(),
                ]);
            }
            if ($course->is_published) {
                $recentActivity->push([
                    'icon'  => 'globe',
                    'color' => '#185fa5',
                    'bg'    => '#e6f1fb',
                    'text'  => 'Cours publié',
                    'sub'   => $course->title,
                    'time'  => $course->created_at->diffForHumans(),
                ]);
            }
        }
        $recentActivity = $recentActivity->take(6);

        return view('dashboard.formateur', compact(
            'courses',
            'totalCourses', 'published', 'drafts',
            'totalStudents', 'totalLessons',
            'revenueEst', 'avgRating',
            'chartLabels', 'chartStudents', 'chartRevenues',
            'recentActivity'
        ));
    }
}