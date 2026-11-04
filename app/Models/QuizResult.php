<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'passed',
        'attempts',
        'completed_at',
    ];

    protected $casts = [
        'score'        => 'integer',
        'passed'       => 'boolean',
        'attempts'     => 'integer',
        'completed_at' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}