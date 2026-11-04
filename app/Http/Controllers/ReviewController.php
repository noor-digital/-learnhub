<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ─────────────────────────────────────────────────
    // store() — Déposer un avis
    // POST /courses/{course}/reviews
    // ─────────────────────────────────────────────────
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = auth()->user();

        // Seulement les étudiants inscrits peuvent laisser un avis
        if (! $user->isEnrolledIn($course)) {
            return back()->with('error', 'Inscris-toi au cours avant de laisser un avis.');
        }

        // Vérification : pas déjà un avis
        $exists = Review::where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà laissé un avis sur ce cours.');
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'rating'    => $validated['rating'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return back()->with('success', '⭐ Merci pour votre avis !');
    }

    // ─────────────────────────────────────────────────
    // destroy() — Supprimer son avis
    // DELETE /reviews/{review}
    // ─────────────────────────────────────────────────
    public function destroy(Review $review): RedirectResponse
    {
        $user = auth()->user();

        // Seulement l'auteur de l'avis ou un admin peut le supprimer
        if ($review->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $courseId = $review->course_id;
        $review->delete();

        return redirect()
            ->route('courses.show', $courseId)
            ->with('success', 'Avis supprimé.');
    }
}