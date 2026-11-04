<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Admin peut tout faire sans vérification
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        $lesson->loadMissing('course');

        // Peut voir si inscrit au cours OU propriétaire du cours
        return $user->isEnrolledIn($lesson->course)
            || $lesson->course->user_id === $user->id;
    }

    public function update(User $user, Lesson $lesson): bool
    {
        $lesson->loadMissing('course');

        // Peut modifier si c'est son cours
        return $lesson->course->user_id === $user->id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        $lesson->loadMissing('course');

        return $lesson->course->user_id === $user->id;
    }
}