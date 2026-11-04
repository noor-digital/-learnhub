<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'video_url',
        'duration',
        'order',
    ];

    protected $casts = [
        'duration' => 'integer',
        'order'    => 'integer',
    ];

    // Un lesson appartient à un cours
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Les utilisateurs qui ont terminé cette leçon
    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot('completed_at')
                    ->withTimestamps();
    }

    // Vérifie si un utilisateur spécifique a terminé cette leçon
    public function isCompletedBy(User $user): bool
    {
        return $this->completedByUsers()
                    ->where('user_id', $user->id)
                    ->exists();
    }

    // Convertit une URL YouTube normale en URL embed pour l'iframe
    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        // Détecte youtu.be/xxx ou youtube.com/watch?v=xxx
        preg_match(
            '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([a-zA-Z0-9_-]{11})/',
            $this->video_url,
            $matches
        );

        if (isset($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Si c'est déjà une URL embed ou autre, retourne telle quelle
        return $this->video_url;
    }
}