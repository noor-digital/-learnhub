<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'pass_score',
        'is_active',
    ];

    protected $casts = [
        'pass_score' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    public function resultForUser(User $user): ?QuizResult
    {
        return $this->results()
                    ->where('user_id', $user->id)
                    ->first();
    }
}