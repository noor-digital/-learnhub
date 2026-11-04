<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Admin peut tout faire
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'formateur']);
    }

    public function update(User $user, Course $course): bool
    {
        return $course->user_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $course->user_id === $user->id;
    }

    public function manageLessons(User $user, Course $course): bool
    {
        // Formateur propriétaire OU admin
        return $course->user_id === $user->id;
    }
}