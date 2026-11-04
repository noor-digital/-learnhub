<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    // ─────────────────────────────────────────────────
    // show() — Affiche le quiz
    // ─────────────────────────────────────────────────
    public function show(Course $course): View|RedirectResponse
    {
        $user = auth()->user();

        abort_unless(
            $user->isAdmin()
                || $course->user_id === $user->id
                || $user->isEnrolledIn($course),
            403,
            'Inscris-toi au cours pour accéder au quiz.'
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

        $existingResult = $quiz->resultForUser($user);

        return view('quizzes.show', compact('course', 'quiz', 'existingResult'));
    }

    // ─────────────────────────────────────────────────
    // submit() — Reçoit les réponses et calcule le score
    // ─────────────────────────────────────────────────
    public function submit(Request $request, Course $course): View
    {
        $user = auth()->user();

        abort_unless(
            $user->isAdmin()
                || $course->user_id === $user->id
                || $user->isEnrolledIn($course),
            403
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
        $submitted = $request->input('answers', []);

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

        // ✅ Sauvegarde dans quiz_results (update si déjà tenté)
        $existing = QuizResult::where('quiz_id', $quiz->id)
                               ->where('user_id', $user->id)
                               ->first();

        if ($existing) {
            $existing->update([
                'score'        => $score,
                'passed'       => $passed,
                'attempts'     => $existing->attempts + 1,
                'completed_at' => now(),
            ]);
            $result = $existing->fresh();
        } else {
            $result = QuizResult::create([
                'quiz_id'      => $quiz->id,
                'user_id'      => $user->id,
                'score'        => $score,
                'passed'       => $passed,
                'attempts'     => 1,
                'completed_at' => now(),
            ]);
        }

        return view('quizzes.result', compact(
            'course', 'quiz', 'result',
            'details', 'score', 'passed', 'correct', 'total'
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

        return view('quizzes.create', compact('course'));
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

        $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'pass_score' => ['required', 'integer', 'min:1', 'max:100'],
            'questions'  => ['required', 'array', 'min:1'],
        ]);

        // Supprime l'ancien quiz (cascade supprime questions + réponses + résultats)
        $course->quizzes()->delete();

        $quiz = $course->quizzes()->create([
            'title'       => trim($request->title),
            'description' => trim($request->description ?? ''),
            'pass_score'  => (int) $request->pass_score,
            'is_active'   => true,
        ]);

        foreach ($request->questions as $qIdx => $qData) {

            $questionText = trim($qData['text'] ?? '');
            if (empty($questionText)) continue;

            $question = $quiz->questions()->create([
                'question_text' => $questionText,
                'order'         => (int) $qIdx + 1,
            ]);

            foreach ($qData['answers'] ?? [] as $aIdx => $aData) {

                $answerText = trim($aData['text'] ?? '');
                if (empty($answerText)) continue;

                $question->answers()->create([
                    'answer_text' => $answerText,
                    'is_correct'  => ($aData['correct'] ?? '0') === '1',
                ]);
            }
        }

        return redirect()
            ->route('courses.show', $course)
            ->with('success', '✅ Quiz "' . $quiz->title . '" créé avec succès !');
    }
}