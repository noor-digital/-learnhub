@extends('layouts.app')
@section('title', 'Résultat — ' . $quiz->title)
@section('content')
<style>
.res-wrap { max-width: 680px; margin: 0 auto; padding: 2rem 1rem; }
.score-ring {
    width: 130px; height: 130px; border-radius: 50%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    margin: 0 auto 1.2rem; border: 7px solid currentColor;
    animation: popIn .4s cubic-bezier(.4,0,.2,1) both;
}
@keyframes popIn {
    from { transform: scale(.6); opacity: 0; }
    to   { transform: scale(1);  opacity: 1; }
}
.score-ring.pass { color: #198754; background: #e1f5ee; }
.score-ring.fail { color: #dc3545; background: #fcebeb; }
.score-num { font-size: 2.2rem; font-weight: 700; line-height: 1; }
.score-pct { font-size: .75rem; font-weight: 600; opacity: .7; }

.d-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: .9rem 1rem; border-radius: 10px; margin-bottom: .5rem;
    font-size: .87rem;
    animation: fadeUp .3s ease both;
}
.d-item.ok { background: #e1f5ee; border-left: 3px solid #198754; }
.d-item.ko { background: #fcebeb; border-left: 3px solid #dc3545; }
@keyframes fadeUp {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<div class="res-wrap">

    <div class="mb-4">
        <a href="{{ route('courses.show', $course) }}"
           style="color:var(--brand);text-decoration:none;font-size:.82rem">
            <i class="bi bi-arrow-left me-1"></i>Retour au cours
        </a>
        <h1 class="h4 fw-bold mt-2 mb-0" style="color:#1a1a2e">Résultat du quiz</h1>
        <p class="text-muted" style="font-size:.85rem">{{ $quiz->title }}</p>
    </div>

    {{-- Score --}}
    <div class="text-center mb-4">
        <div class="score-ring {{ $passed ? 'pass' : 'fail' }}">
            <div class="score-num">{{ $score }}</div>
            <div class="score-pct">%</div>
        </div>

        @if($passed)
            <div style="font-size:1.8rem">🏆</div>
            <h2 class="h5 fw-bold text-success my-1">Quiz réussi !</h2>
        @else
            <h2 class="h5 fw-bold text-danger my-1">Quiz non validé</h2>
        @endif

        <p class="text-muted mb-3" style="font-size:.87rem">
            {{ $correct }} / {{ $total }} bonnes réponses
            &nbsp;·&nbsp; Requis : {{ $quiz->pass_score }}%
            @if($isPreview)
                &nbsp;·&nbsp; Prévisualisation (non enregistrée)
            @else
                &nbsp;·&nbsp; Tentative n°{{ $result->attempts }}
            @endif
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('quiz.show', $course) }}"
               class="btn btn-sm fw-semibold px-4"
               style="background:var(--brand);color:#fff;
                      border-radius:8px;border:none">
                <i class="bi bi-arrow-clockwise me-1"></i>Retenter
            </a>

            @php
                $prog = auth()->user()->enrolledCourses()
                    ->where('course_id', $course->id)
                    ->first()?->pivot->progress ?? 0;
            @endphp
            @if(!$isPreview && $prog >= 100)
                <a href="{{ route('certificate.generate', $course) }}"
                   class="btn btn-sm fw-semibold px-4"
                   style="background:#198754;color:#fff;
                          border-radius:8px;border:none">
                    <i class="bi bi-award me-1"></i>Certificat
                </a>
            @endif

            <a href="{{ route('courses.show', $course) }}"
               class="btn btn-sm btn-outline-secondary px-4"
               style="border-radius:8px">
                Retour au cours
            </a>
        </div>
    </div>

    {{-- Correction --}}
    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-header bg-white border-0 pt-3 px-4">
            <h2 class="h6 fw-bold mb-0">
                <i class="bi bi-list-check me-2" style="color:var(--brand)"></i>
                Correction détaillée
            </h2>
        </div>
        <div class="card-body px-4 pb-4 pt-2">
            @foreach($details as $i => $d)
            <div class="d-item {{ $d['is_correct'] ? 'ok' : 'ko' }}"
                 style="animation-delay:{{ $i * 55 }}ms">
                <div style="flex-shrink:0;margin-top:2px">
                    <i class="bi bi-{{ $d['is_correct']
                        ? 'check-circle-fill text-success'
                        : 'x-circle-fill text-danger' }}"
                       style="font-size:1rem"></i>
                </div>
                <div>
                    <div class="fw-semibold mb-1" style="color:#1a1a2e">
                        {{ $i + 1 }}. {{ $d['question'] }}
                    </div>
                    <div style="font-size:.83rem;color:#555">
                        <span class="text-muted" style="font-size:.75rem">
                            Votre réponse :
                        </span>
                        {{ $d['submitted'] }}
                    </div>
                    @if(! $d['is_correct'])
                    <div style="font-size:.83rem;color:#0f6e56;margin-top:3px">
                        <i class="bi bi-check2 me-1"></i>
                        <span class="text-muted" style="font-size:.75rem">
                            Bonne réponse :
                        </span>
                        {{ $d['correct_answer'] }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection