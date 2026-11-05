<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FormateurDashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil (publique)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| Routes protégées (utilisateur connecté)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ── Profil ──────────────────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Dashboards ──────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/student',   [StudentDashboardController::class,   'index'])->name('dashboard.student');
    Route::get('/dashboard/formateur', [FormateurDashboardController::class, 'index'])->name('dashboard.formateur');
    Route::get('/dashboard/admin',     [AdminDashboardController::class,     'index'])->name('dashboard.admin');

    // ── Cours (/create DOIT être avant /{course}) ───────────────────────
    Route::get('/courses',                 [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create',          [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses',                [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}',        [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit',   [CourseController::class, 'edit'])->name('courses.edit');
    Route::match(['put', 'patch'], '/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}',     [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/restore', [CourseController::class, 'restore'])->name('courses.restore');

    // ── Leçons (/create DOIT être avant /{lesson}) ──────────────────────
    Route::get('/courses/{course}/lessons/create',        [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons',              [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}',      [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}',      [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}',   [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/lessons/{lesson}/complete',             [LessonController::class, 'markComplete'])->name('lessons.complete');

    // ── Inscriptions ────────────────────────────────────────────────────
    Route::post('/courses/{course}/enroll',   [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::delete('/courses/{course}/enroll', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');

    // ── Avis ────────────────────────────────────────────────────────────
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}',       [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ── Quiz ────────────────────────────────────────────────────────────
    Route::get('/courses/{course}/quiz',        [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/courses/{course}/quiz',       [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/courses/{course}/quiz/create', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('/courses/{course}/quiz/store', [QuizController::class, 'store'])->name('quiz.store');

    // ── Paiement ────────────────────────────────────────────────────────
    Route::get('/courses/{course}/payment',  [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/courses/{course}/payment', [PaymentController::class, 'process'])->name('payment.process');

    // ── Certificat ──────────────────────────────────────────────────────
    Route::get('/courses/{course}/certificate', [CertificateController::class, 'generate'])->name('certificate.generate');
});

require __DIR__ . '/auth.php';