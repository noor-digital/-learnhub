@extends('layouts.app')
@section('title', 'Quiz — ' . $course->title)
@section('content')
<style>
.quiz-shell {
    min-height: calc(100vh - var(--nav-h));
    background: #f8f7f4;
    display: flex; flex-direction: column;
}

/* ── Top bar ────────────────────────────────────────── */
.quiz-topbar {
    background: var(--navy);
    padding: .75rem 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.quiz-topbar-title {
    color: #fff; font-size: .88rem; font-weight: 600;
    flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.quiz-top-prog {
    flex: 1; height: 6px; background: rgba(255,255,255,.15);
    border-radius: 999px; overflow: hidden;
}
.quiz-top-fill {
    height: 100%; background: var(--brand);
    border-radius: 999px; transition: width .4s ease;
}
.quiz-top-count {
    color: #9b9bc4; font-size: .78rem; white-space: nowrap;
}

/* ── Main area ─────────────────────────────────────── */
.quiz-main {
    flex: 1; display: flex;
    align-items: flex-start; justify-content: center;
    padding: 2.5rem 1rem;
}
.quiz-box { width: 100%; max-width: 680px; }

/* ── Question card ─────────────────────────────────── */
.q-card {
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 16px; overflow: hidden;
    margin-bottom: 1.25rem;
    animation: fadeUp .35s ease both;
    display: none;
}
.q-card.active { display: block; }
@keyframes fadeUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
.q-top {
    background: var(--navy); padding: 1.25rem 1.4rem;
    color: #fff;
}
.q-label {
    font-size: .7rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; color: #9b9bc4; margin-bottom: .35rem;
}
.q-text { font-size: 1.05rem; font-weight: 600; line-height: 1.4; }
.q-body { padding: 1.25rem 1.4rem; }

/* ── Answer options ────────────────────────────────── */
.ans-opt {
    display: flex; align-items: center; gap: 13px;
    padding: .85rem 1rem; border-radius: 10px;
    border: 1.5px solid #e8e6e0;
    cursor: pointer; transition: all .15s;
    margin-bottom: .6rem; font-size: .9rem; color: #333;
    user-select: none;
}
.ans-opt:hover { border-color: var(--brand); background: #f3f2ff; color: var(--brand); }
.ans-opt input { display: none; }
.ans-opt:has(input:checked) {
    border-color: var(--brand); background: #eeedfe;
    color: var(--brand); font-weight: 500;
}
.ans-dot {
    width: 32px; height: 32px; border-radius: 50%;
    background: #f0efe9; border: 2px solid #e8e6e0;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700; color: #888;
    flex-shrink: 0; transition: all .15s;
}
.ans-opt:has(input:checked) .ans-dot {
    background: var(--brand); border-color: var(--brand); color: #fff;
}

/* ── Navigation quiz ───────────────────────────────── */
.q-nav {
    display: flex; justify-content: space-between;
    align-items: center; margin-top: .75rem;
}
.qnav-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: .6rem 1.2rem; border-radius: 9px;
    font-size: .88rem; font-weight: 500;
    cursor: pointer; transition: all .15s; border: none;
}
.qnav-prev {
    background: #f0efe9; color: #555;
}
.qnav-prev:hover { background: #e8e6e0; }
.qnav-next {
    background: var(--brand); color: #fff;
}
.qnav-next:hover { background: #5a52d5; transform: translateY(-1px); }
.qnav-submit {
    background: #198754; color: #fff;
}
.qnav-submit:hover { background: #157347; }

/* ── Stats bar (mobile) ────────────────────────────── */
.quiz-stats {
    display: flex; gap: .75rem; flex-wrap: wrap;
    margin-bottom: 1.25rem;
}
.qstat {
    display: flex; align-items: center; gap: 6px;
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 8px; padding: .45rem .85rem;
    font-size: .8rem; color: #555;
}
.qstat i { color: var(--brand); }

/* ── Attempt history ───────────────────────────────── */
.att-hist {
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 12px; padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
}
.att-row {
    display: flex; justify-content: space-between;
    align-items: center; padding: .4rem 0;
    border-bottom: 1px solid #f4f3ef;
    font-size: .82rem;
}
.att-row:last-child { border-bottom: none; }
</style>

<form method="POST"
      action="{{ route('quiz.submit', $course) }}"
      id="quiz-form">
@csrf

<div class="quiz-shell">

    {{-- Top bar --}}
    <div class="quiz-topbar">
        <a href="{{ route('courses.show', $course) }}"
           style="color:#9b9bc4;text-decoration:none;font-size:.88rem;flex-shrink:0">
            <i class="bi bi-x-lg"></i>
        </a>
        <div class="quiz-topbar-title">{{ $quiz->title }}</div>
        <div class="quiz-top-prog">
            <div class="quiz-top-fill" id="top-fill" style="width:0%"></div>
        </div>
        <span class="quiz-top-count">
            <span id="cur-q">1</span> / {{ $quiz->questions->count() }}
        </span>
    </div>

    {{-- Main --}}
    <div class="quiz-main">
        <div class="quiz-box">

            {{-- Stats --}}
            <div class="quiz-stats">
                <div class="qstat">
                    <i class="bi bi-question-circle"></i>
                    {{ $quiz->questions->count() }} questions
                </div>
                <div class="qstat">
                    <i class="bi bi-check-circle"></i>
                    Score requis : {{ $quiz->pass_score }}%
                </div>
                @if($existingResult)
                <div class="qstat" style="border-color:{{ $existingResult->passed ? '#198754' : '#dc3545' }}">
                    <i class="bi bi-{{ $existingResult->passed ? 'trophy' : 'x-circle' }}"
                       style="color:{{ $existingResult->passed ? '#198754' : '#dc3545' }}"></i>
                    Dernier : {{ $existingResult->score }}%
                    ({{ $existingResult->attempts }} essai{{ $existingResult->attempts>1?'s':'' }})
                </div>
                @endif
            </div>

            {{-- Historique tentatives --}}
            @if($existingResult && $existingResult->attempts > 1)
            <div class="att-hist mb-3">
                <div style="font-size:.8rem;font-weight:600;color:#1a1a2e;margin-bottom:.5rem">
                    <i class="bi bi-clock-history me-1" style="color:var(--brand)"></i>
                    Historique des tentatives
                </div>
                @for($t = 1; $t <= $existingResult->attempts; $t++)
                <div class="att-row">
                    <span>Tentative {{ $t }}</span>
                    @if($t === $existingResult->attempts)
                    <span style="color:{{ $existingResult->passed ? '#198754' : '#dc3545' }};font-weight:600">
                        {{ $existingResult->score }}%
                        @if($existingResult->passed) ✓ @else ✗ @endif
                    </span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </div>
                @endfor
            </div>
            @endif

            {{-- Questions --}}
            @foreach($quiz->questions as $question)
            <div class="q-card {{ $loop->first ? 'active' : '' }}"
                 id="q-{{ $loop->index }}">
                <div class="q-top">
                    <div class="q-label">
                        Question {{ $loop->iteration }} / {{ $quiz->questions->count() }}
                    </div>
                    <div class="q-text">{{ $question->question_text }}</div>
                </div>
                <div class="q-body">
                    @foreach($question->answers as $answer)
                    <label class="ans-opt"
                           for="ans_{{ $question->id }}_{{ $answer->id }}">
                        <input
                            type="radio"
                            name="answers[{{ $question->id }}]"
                            id="ans_{{ $question->id }}_{{ $answer->id }}"
                            value="{{ $answer->id }}"
                            onchange="onAns({{ $loop->parent->index }})">
                        <div class="ans-dot">{{ chr(65 + $loop->index) }}</div>
                        <span>{{ $answer->answer_text }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Navigation --}}
            <div class="q-nav" id="nav-{{ $loop->index }}"
                 style="{{ $loop->first ? '' : 'display:none' }}">
                @if(!$loop->first)
                <button type="button" class="qnav-btn qnav-prev"
                        onclick="goQ({{ $loop->index - 1 }})">
                    <i class="bi bi-arrow-left"></i> Précédente
                </button>
                @else
                <div></div>
                @endif

                @if(!$loop->last)
                <button type="button" class="qnav-btn qnav-next"
                        id="next-{{ $loop->index }}"
                        onclick="goQ({{ $loop->index + 1 }})">
                    Suivante <i class="bi bi-arrow-right"></i>
                </button>
                @else
                <button type="button" class="qnav-btn qnav-submit"
                        onclick="submitQuiz()">
                    <i class="bi bi-send me-1"></i>Soumettre
                </button>
                @endif
            </div>
            @endforeach

        </div>
    </div>
</div>

</form>

<script>
const TOTAL = {{ $quiz->questions->count() }};
let current = 0;
const answered = new Set();

function goQ(to) {
    // Quitte actuel
    document.getElementById('q-' + current).classList.remove('active');
    document.getElementById('nav-' + current).style.display = 'none';
    // Affiche cible
    current = to;
    document.getElementById('q-' + to).classList.add('active');
    document.getElementById('nav-' + to).style.display = 'flex';
    // Update top bar
    document.getElementById('cur-q').textContent = to + 1;
    document.getElementById('top-fill').style.width =
        ((to + 1) / TOTAL * 100) + '%';
}

function onAns(qIdx) {
    answered.add(qIdx);
}

function submitQuiz() {
    if (answered.size < TOTAL) {
        const missing = TOTAL - answered.size;
        if (!confirm(`${missing} question(s) sans réponse. Soumettre quand même ?`)) return;
    }
    document.getElementById('quiz-form').submit();
}
</script>
@endsection