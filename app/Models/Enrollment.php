<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
    'user_id',
    'course_id',
    'enrolled_at',
    'progress',
    'payment_status',
];

    protected $casts = [
    'enrolled_at' => 'datetime',
    'progress'    => 'integer',
];

    // Un enrollment appartient à un utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un enrollment appartient à un cours
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
