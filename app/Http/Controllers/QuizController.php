<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuizController extends Controller
{
    // ─────────────────────────────────────────────────
    // show() — Affiche le quiz
    // ─────────────────────────────────────────────────
    public function show(Course $course): View|RedirectResponse
    {
        $user = auth()->user();

        $isPreview = $user->isAdmin() || (int) $course->user_id === (int) $user->id;

        abort_unless(
            $isPreview || ($course->is_published && $course->isAccessibleBy($user)),
            403,
            'Un étudiant doit être inscrit et disposer d’un accès actif à un cours publié pour passer ce quiz.'
        );

        $quiz = $course->quizzes()
            ->where('is_active', true)
            ->with(['questions' => fn($q) => $q->orderBy('order')->with('answers')])
            ->first();

        if (! $quiz) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'Aucun quiz disponible pour ce cours.');
        }

        // Mélange les réponses ici (côté controller, pas dans la vue)
        $quiz->questions->each(function ($question) {
            $question->setRelation('answers', $question->answers->shuffle());
        });

        $existingResult = $isPreview ? null : $quiz->resultForUser($user);

        return view('quizzes.show', compact('course', 'quiz', 'existingResult', 'isPreview'));
    }

    // ─────────────────────────────────────────────────
    // submit() — Reçoit les réponses et calcule le score
    // ─────────────────────────────────────────────────
    public function submit(Request $request, Course $course): View
    {
        $user = auth()->user();

        $isPreview = $user->isAdmin() || (int) $course->user_id === (int) $user->id;

        abort_unless(
            $isPreview || ($course->is_published && $course->isAccessibleBy($user)),
            403,
            'Un étudiant doit être inscrit et disposer d’un accès actif à un cours publié pour passer ce quiz.'
        );

        // ✅ Charge le quiz avec toutes les questions ET réponses
        $quiz = $course->quizzes()
            ->where('is_active', true)
            ->with(['questions' => fn($q) => $q->orderBy('order')->with('answers')])
            ->firstOrFail();

        /*
         * ✅ Structure attendue depuis le formulaire :
         *    answers[{question_id}] = {answer_id}
         *
         * Exemple : answers[12] = 45
         * signifie : pour la question id=12, l'étudiant a choisi la réponse id=45
         */
        $validatedAnswers = $request->validate([
            'answers'   => ['nullable', 'array'],
            'answers.*' => ['nullable', 'integer'],
        ]);
        $submitted = $validatedAnswers['answers'] ?? [];

        $total   = $quiz->questions->count();
        $correct = 0;
        $details = [];

        foreach ($quiz->questions as $question) {

            // ID de la réponse soumise pour cette question
            $submittedAnswerId = isset($submitted[$question->id])
                ? (int) $submitted[$question->id]
                : null;

            // Bonne réponse de cette question (depuis la collection déjà chargée)
            $correctAnswer = $question->answers
                ->first(fn($a) => $a->is_correct === true);

            // Réponse soumise (depuis la collection, pas de requête SQL)
            $submittedAnswer = $submittedAnswerId
                ? $question->answers->first(fn($a) => $a->id === $submittedAnswerId)
                : null;

            // ✅ Vérifie si la réponse est correcte
            $isCorrect = $submittedAnswer !== null && $submittedAnswer->is_correct === true;

            if ($isCorrect) {
                $correct++;
            }

            $details[] = [
                'question'       => $question->question_text,
                'submitted'      => $submittedAnswer?->answer_text ?? 'Non répondu',
                'correct_answer' => $correctAnswer?->answer_text  ?? '—',
                'is_correct'     => $isCorrect,
            ];
        }

        // ✅ Calcul du score
        $score  = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
        $passed = $score >= $quiz->pass_score;

        // Une prévisualisation admin/formateur calcule le score à l'écran
        // mais ne crée et ne modifie jamais de statistique étudiante.
        if ($isPreview) {
            $result = new QuizResult([
                'score' => $score,
                'passed' => $passed,
                'attempts' => 0,
                'completed_at' => now(),
            ]);
        } else {
            $result = DB::transaction(function () use ($quiz, $user, $score, $passed) {
                $existing = QuizResult::where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $existing->update([
                        'score'        => $score,
                        'passed'       => $passed,
                        'attempts'     => $existing->attempts + 1,
                        'completed_at' => now(),
                    ]);

                    return $existing->fresh();
                }

                return QuizResult::create([
                    'quiz_id'      => $quiz->id,
                    'user_id'      => $user->id,
                    'score'        => $score,
                    'passed'       => $passed,
                    'attempts'     => 1,
                    'completed_at' => now(),
                ]);
            });
        }

        return view('quizzes.result', compact(
            'course', 'quiz', 'result',
            'details', 'score', 'passed', 'correct', 'total', 'isPreview'
        ));
    }

    // ─────────────────────────────────────────────────
    // create() — Formulaire de création
    // ─────────────────────────────────────────────────
    public function create(Course $course): View
    {
        abort_unless(
            auth()->user()->isAdmin() || $course->user_id === auth()->id(),
            403
        );

        $hasActiveQuiz = $course->quizzes()->where('is_active', true)->exists();

        return view('quizzes.create', compact('course', 'hasActiveQuiz'));
    }

    // ─────────────────────────────────────────────────
    // store() — Enregistre le quiz
    // ─────────────────────────────────────────────────
    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_unless(
            auth()->user()->isAdmin() || $course->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title'                         => ['required', 'string', 'max:255'],
            'description'                   => ['nullable', 'string', 'max:5000'],
            'pass_score'                    => ['required', 'integer', 'min:1', 'max:100'],
            'questions'                     => ['required', 'array', 'min:1'],
            'questions.*.text'               => ['required', 'string', 'max:2000'],
            'questions.*.answers'            => ['required', 'array', 'min:2'],
            'questions.*.answers.*.text'     => ['required', 'string', 'max:255'],
            'questions.*.answers.*.correct'  => ['required', 'in:0,1'],
        ]);

        // Chaque question doit avoir exactement une bonne réponse.
        foreach ($validated['questions'] as $questionIndex => $questionData) {
            $correctAnswers = collect($questionData['answers'])
                ->filter(fn ($answer) => $answer['correct'] === '1')
                ->count();

            if ($correctAnswers !== 1) {
                throw ValidationException::withMessages([
                    "questions.{$questionIndex}.answers" =>
                        'Chaque question doit avoir exactement une bonne réponse.',
                ]);
            }
        }

        // Chaque modification crée une nouvelle version du quiz.
        // L'ancienne version est conservée inactive avec ses questions et
        // ses résultats : aucune tentative historique n'est supprimée.
        $quiz = DB::transaction(function () use ($course, $validated) {
            $activeQuizzes = $course->quizzes()
                ->where('is_active', true)
                ->lockForUpdate()
                ->get();

            foreach ($activeQuizzes as $activeQuiz) {
                $activeQuiz->update(['is_active' => false]);
            }

            $quiz = $course->quizzes()->create([
                'title'       => trim($validated['title']),
                'description' => trim($validated['description'] ?? ''),
                'pass_score'  => (int) $validated['pass_score'],
                'is_active'   => true,
            ]);

            foreach ($validated['questions'] as $qIdx => $qData) {
                $question = $quiz->questions()->create([
                    'question_text' => trim($qData['text']),
                    'order'         => (int) $qIdx + 1,
                ]);

                foreach ($qData['answers'] as $aData) {
                    $question->answers()->create([
                        'answer_text' => trim($aData['text']),
                        'is_correct'  => $aData['correct'] === '1',
                    ]);
                }
            }

            return $quiz;
        });

        return redirect()
            ->route('courses.show', $course)
            ->with('success', '✅ Nouvelle version du quiz « ' . $quiz->title . ' » enregistrée. Les anciennes tentatives sont conservées.');
    }
}