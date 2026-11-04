<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Sans ces deux lignes, authorize() ne fonctionne pas
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);

        // Politique de mot de passe appliquée via Password::defaults()
        // (changement depuis le profil + réinitialisation par email).
        // L'inscription garde sa propre règle (Password::min(8)).
        Password::defaults(fn () => Password::min(8)->letters()->mixedCase()->numbers());
    }
}