<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'price',
        'is_published',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'price'        => 'decimal:2',
    ];

    // ── Relations ─────────────────────────────────────

    public function formateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('progress', 'enrolled_at', 'payment_status')
                    ->withTimestamps();
    }

    // ── Méthodes ──────────────────────────────────────

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function enrollmentsCount(): int
    {
        return $this->students()->count();
    }

    public function isAccessibleBy(User $user): bool
    {
        // Les administrateurs et le formateur propriétaire peuvent prévisualiser
        // un cours, même lorsqu'il n'est pas encore publié.
        if ($user->isAdmin() || (int) $this->user_id === (int) $user->id) {
            return true;
        }

        // Un étudiant ne peut jamais accéder à un cours non publié.
        if (! $this->is_published) {
            return false;
        }

        // L'accès dépend du statut enregistré lors de l'inscription,
        // et non du prix actuel du cours (qui peut changer).
        return $this->students()
            ->where('enrollments.user_id', $user->id)
            ->whereIn('enrollments.payment_status', ['free', 'paid'])
            ->exists();
    }

    // Récupère la progression de l'utilisateur pour ce cours
        public function progressForUser(User $user): int
        {
            $enrollment = $this->students()
                ->where('enrollments.user_id', $user->id)
                ->first();

            return $enrollment ? (int) $enrollment->pivot->progress : 0;
        }
}
