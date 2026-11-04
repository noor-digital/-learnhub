<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['user_id', 'course_id', 'rating', 'comment'];

    protected $casts = ['rating' => 'integer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Retourne les étoiles sous forme de chaîne (ex: "★★★☆☆")
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating)
             . str_repeat('☆', 5 - $this->rating);
    }
}