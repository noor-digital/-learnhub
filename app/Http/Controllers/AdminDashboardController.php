<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\QuizResult;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        // ── KPIs globaux ───────────────────────────────
        $totalUsers      = User::count();
        $totalFormateurs = User::where('role', 'formateur')->count();
        $totalEtudiants  = User::where('role', 'etudiant')->count();
        $totalCourses    = Course::count();
        $publishedCourses= Course::whereNull('archived_at')->where('is_published', true)->count();
        $totalLessons    = DB::table('lessons')->count();
        $totalEnrollments= Enrollment::count();
        $totalQuizzes    = QuizResult::count();
        $totalReviews    = Review::count();

        // Estimation indicative des paiements simulés : seuls les statuts
        // paid sont comptés. Ce montant n'est pas un revenu réellement encaissé.
        $revenueGlobal = Course::whereNull('archived_at')->withCount([
                'students as paid_students_count' => fn ($query) =>
                    $query->where('enrollments.payment_status', 'paid'),
            ])
            ->get()
            ->sum(fn ($course) => (float) $course->price * $course->paid_students_count);

        // ── Derniers utilisateurs ──────────────────────
        $latestUsers = User::latest()
            ->take(8)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        // ── Derniers cours ─────────────────────────────
        $latestCourses = Course::whereNull('archived_at')->with(['formateur:id,name', 'category:id,name'])
            ->withCount('students')
            ->latest()
            ->take(6)
            ->get();

        // ── Top cours par inscrits ─────────────────────
        $topCourses = Course::whereNull('archived_at')->withCount('students')
            ->withAvg('reviews', 'rating')
            ->with('formateur:id,name')
            ->orderByDesc('students_count')
            ->take(5)
            ->get();

        // ── Graphique : inscriptions 6 derniers mois ───
        $enrollmentsByMonth = Enrollment::selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month,
                 COUNT(*) as total"
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months       = collect();
        $monthLabels  = collect();
        $monthValues  = collect();
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $lbl = now()->subMonths($i)->isoFormat('MMM YY');
            $monthLabels->push($lbl);
            $monthValues->push($enrollmentsByMonth->get($key)?->total ?? 0);
        }

        // ── Graphique : nouveaux users par mois ────────
        $usersByMonth = User::selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month,
                 COUNT(*) as total"
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $usersMonthValues = collect();
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $usersMonthValues->push($usersByMonth->get($key)?->total ?? 0);
        }

        // ── Activité récente (feed) ────────────────────
        $activity = collect();

        // Dernières inscriptions
        Enrollment::with(['user:id,name', 'course:id,title'])
            ->latest()
            ->take(4)
            ->get()
            ->each(fn($e) => $activity->push([
                'icon'  => 'person-plus',
                'color' => '#0f6e56',
                'bg'    => '#e1f5ee',
                'title' => ($e->user?->name ?? 'Un étudiant') . ' s\'est inscrit',
                'sub'   => $e->course?->title ?? '—',
                'time'  => $e->created_at->diffForHumans(),
                'ts'    => $e->created_at,
            ]));

        // Derniers cours publiés
        Course::whereNull('archived_at')
            ->where('is_published', true)
            ->with('formateur:id,name')
            ->latest()
            ->take(3)
            ->get()
            ->each(fn($c) => $activity->push([
                'icon'  => 'globe',
                'color' => '#185fa5',
                'bg'    => '#e6f1fb',
                'title' => 'Cours publié',
                'sub'   => $c->title,
                'time'  => $c->updated_at->diffForHumans(),
                'ts'    => $c->updated_at,
            ]));

        // Derniers quiz complétés
        QuizResult::with(['user:id,name', 'quiz:id,title'])
            ->latest()
            ->take(3)
            ->get()
            ->each(fn($r) => $activity->push([
                'icon'  => 'patch-check',
                'color' => '#993c1d',
                'bg'    => '#faece7',
                'title' => ($r->user?->name ?? 'Un étudiant') . ' a complété un quiz',
                'sub'   => ($r->quiz?->title ?? '—') . ' — ' . $r->score . '%',
                'time'  => $r->created_at->diffForHumans(),
                'ts'    => $r->created_at,
            ]));

        $activity = $activity->sortByDesc('ts')->take(8)->values();

        // ── Répartition rôles (donut) ──────────────────
        $rolesData = [
            'labels' => ['Admins', 'Formateurs', 'Étudiants'],
            'data'   => [
                User::where('role', 'admin')->count(),
                $totalFormateurs,
                $totalEtudiants,
            ],
        ];

        return view('dashboard.admin', compact(
            'totalUsers', 'totalFormateurs', 'totalEtudiants',
            'totalCourses', 'publishedCourses', 'totalLessons',
            'totalEnrollments', 'totalQuizzes', 'totalReviews',
            'revenueGlobal',
            'latestUsers', 'latestCourses', 'topCourses',
            'monthLabels', 'monthValues', 'usersMonthValues',
            'activity', 'rolesData'
        ));
    }
}