<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAccessAndQuizSafetyTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'etudiant'): User
    {
        return User::create([
            'name' => ucfirst($role) . ' Test',
            'email' => $role . '-' . uniqid() . '@example.test',
            'password' => 'Password123!',
            'role' => $role,
        ]);
    }

    private function makeCourse(User $owner, bool $published = true, float $price = 0): Course
    {
        $category = Category::create([
            'name' => 'Catégorie test',
            'slug' => 'categorie-' . uniqid(),
        ]);

        return Course::create([
            'title' => 'Cours de test',
            'description' => 'Cours créé pour les tests automatisés.',
            'price' => $price,
            'is_published' => $published,
            'user_id' => $owner->id,
            'category_id' => $category->id,
        ]);
    }

    private function enroll(User $student, Course $course, string $status, int $progress = 0): Enrollment
    {
        return Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress' => $progress,
            'payment_status' => $status,
        ]);
    }

    public function test_pending_enrollment_never_grants_course_access(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner, true, 250);
        $this->enroll($student, $course, 'pending');

        $this->assertFalse($student->fresh()->isEnrolledIn($course));
        $this->assertFalse($course->fresh()->isAccessibleBy($student->fresh()));
    }

    public function test_free_and_paid_enrollments_remain_valid_when_course_price_changes(): void
    {
        $owner = $this->makeUser('formateur');
        $freeStudent = $this->makeUser();
        $paidStudent = $this->makeUser();
        $course = $this->makeCourse($owner, true, 250);

        $this->enroll($freeStudent, $course, 'free');
        $this->enroll($paidStudent, $course, 'paid');

        $this->assertTrue($course->fresh()->isAccessibleBy($freeStudent->fresh()));
        $this->assertTrue($course->fresh()->isAccessibleBy($paidStudent->fresh()));
    }

    public function test_student_cannot_enroll_in_an_unpublished_course(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner, false);

        $this->actingAs($student)
            ->post(route('enrollments.store', $course))
            ->assertNotFound();

        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_pending_student_cannot_mark_a_lesson_complete(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner);
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Leçon protégée',
            'duration' => 10,
            'order' => 1,
        ]);
        $this->enroll($student, $course, 'pending');

        $this->actingAs($student)
            ->post(route('lessons.complete', $lesson))
            ->assertForbidden();

        $this->assertDatabaseMissing('lesson_user', [
            'lesson_id' => $lesson->id,
            'user_id' => $student->id,
        ]);
    }

    public function test_unpublished_course_cannot_be_paid_for_through_demo_payment(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner, false, 100);

        $this->actingAs($student)
            ->get(route('payment.show', $course))
            ->assertNotFound();

        $this->actingAs($student)
            ->post(route('payment.process', $course))
            ->assertNotFound();

        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_instructor_quiz_preview_does_not_create_student_result(): void
    {
        $owner = $this->makeUser('formateur');
        $course = $this->makeCourse($owner, false);
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Quiz de prévisualisation',
            'pass_score' => 70,
            'is_active' => true,
        ]);
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Question de test ?',
            'order' => 1,
        ]);
        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Bonne réponse',
            'is_correct' => true,
        ]);

        $this->actingAs($owner)
            ->post(route('quiz.submit', $course), [
                'answers' => [$question->id => $answer->id],
            ])
            ->assertOk();

        $this->assertDatabaseMissing('quiz_results', [
            'quiz_id' => $quiz->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_updating_quiz_keeps_existing_student_results(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner);
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Ancien titre',
            'pass_score' => 70,
            'is_active' => true,
        ]);
        $result = QuizResult::create([
            'quiz_id' => $quiz->id,
            'user_id' => $student->id,
            'score' => 85,
            'passed' => true,
            'attempts' => 2,
            'completed_at' => now(),
        ]);

        $this->actingAs($owner)
            ->post(route('quiz.store', $course), [
                'title' => 'Quiz mis à jour',
                'description' => 'Nouvelle description',
                'pass_score' => 75,
                'questions' => [
                    '1' => [
                        'text' => 'Nouvelle question ?',
                        'answers' => [
                            '1' => ['text' => 'Réponse A', 'correct' => '1'],
                            '2' => ['text' => 'Réponse B', 'correct' => '0'],
                        ],
                    ],
                ],
            ])
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'Quiz mis à jour',
        ]);
        $this->assertDatabaseHas('quiz_results', [
            'id' => $result->id,
            'quiz_id' => $quiz->id,
            'user_id' => $student->id,
            'score' => 85,
            'attempts' => 2,
        ]);
    }

    public function test_pending_student_cannot_generate_a_certificate_even_at_stored_100_percent(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner);
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Leçon terminée',
            'duration' => 10,
            'order' => 1,
        ]);
        $this->enroll($student, $course, 'pending', 100);
        $lesson->completedByUsers()->attach($student->id, [
            'completed_at' => now(),
        ]);

        $this->actingAs($student)
            ->get(route('certificate.generate', $course))
            ->assertForbidden();
    }

    public function test_simulated_payment_activates_pending_enrollment_without_resetting_progress(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner, true, 250);
        $enrollment = $this->enroll($student, $course, 'pending', 45);

        $this->actingAs($student)
            ->post(route('payment.process', $course))
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'payment_status' => 'paid',
            'progress' => 45,
        ]);
    }

    public function test_free_enrollment_activates_pending_record_without_resetting_progress(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $course = $this->makeCourse($owner, true, 0);
        $enrollment = $this->enroll($student, $course, 'pending', 30);

        $this->actingAs($student)
            ->post(route('enrollments.store', $course))
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'payment_status' => 'free',
            'progress' => 30,
        ]);
    }

    public function test_pending_enrollments_are_not_returned_as_active_student_courses(): void
    {
        $owner = $this->makeUser('formateur');
        $student = $this->makeUser();
        $pendingCourse = $this->makeCourse($owner);
        $activeCourse = $this->makeCourse($owner);

        $this->enroll($student, $pendingCourse, 'pending');
        $this->enroll($student, $activeCourse, 'free');

        $courseIds = $student->fresh()->enrolledCourses()->pluck('courses.id')->all();

        $this->assertSame([$activeCourse->id], $courseIds);
    }
}
