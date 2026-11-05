<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Helpers rôles ──────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFormateur(): bool
    {
        return $this->role === 'formateur';
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    // ── Relations ──────────────────────────────────
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('progress', 'enrolled_at', 'payment_status')
                    ->withTimestamps();
    }

    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrolledCourses()
                    ->where('courses.id', $course->id)
                    ->whereIn('enrollments.payment_status', ['free', 'paid'])
                    ->exists();
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
                    ->withPivot('completed_at')
                    ->withTimestamps();
    }
}