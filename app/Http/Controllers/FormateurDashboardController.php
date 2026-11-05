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

        // Les cours archivés restent visibles au propriétaire pour restauration,
        // mais ne sont plus comptés dans les indicateurs d'activité.
        $activeCourses = $courses->whereNull('archived_at');

        // KPIs — variables directes, pas de tableau imbriqué
        $totalCourses  = $activeCourses->count();
        $published     = $activeCourses->where('is_published', true)->count();
        $drafts        = $activeCourses->where('is_published', false)->count();
        $totalStudents = $activeCourses->sum('students_count');
        $totalLessons  = $activeCourses->sum('lessons_count');
        // Estimation basée uniquement sur les inscriptions paid.
        // Le paiement étant simulé, ce montant n'est pas un revenu encaissé.
        $revenueEst    = $activeCourses->sum(
            fn ($course) => (float) $course->price * $course->paid_students_count
        );
        $avgRating     = round(
            $activeCourses->whereNotNull('reviews_avg_rating')
                    ->avg('reviews_avg_rating') ?? 0,
            1
        );

        // Top 5 par inscrits → graphique
        $chartLabels   = $activeCourses->sortByDesc('students_count')
            ->take(5)->pluck('title')
            ->map(fn($t) => \Str::limit($t, 20))->values();

        $chartStudents = $activeCourses->sortByDesc('students_count')
            ->take(5)->pluck('students_count')->values();

        $chartRevenues = $activeCourses->sortByDesc('students_count')
            ->take(5)
            ->map(fn($c) => round((float) $c->price * $c->paid_students_count))
            ->values();

        // Activité récente simulée (inscriptions récentes)
        $recentActivity = collect();
        foreach ($activeCourses->take(5) as $course) {
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