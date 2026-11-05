@extends('layouts.app')
@section('title', $course->title)
@section('content')
@if($course->archived_at && ((int) auth()->id() === (int) $course->user_id || auth()->user()->isAdmin()))
    <div class="container mx-auto my-4 px-4">
        <div class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-900">
            <strong>Cours archivé.</strong> Son historique est conservé et il n'est plus visible dans le catalogue.
            <form method="POST" action="{{ route('courses.restore', $course) }}" class="mt-3">
                @csrf
                <button type="submit" class="rounded bg-amber-700 px-4 py-2 text-white">
                    Restaurer comme brouillon
                </button>
            </form>
        </div>
    </div>
@endif
<style>
/* ── Hero ──────────────────────────────────────────── */
.course-hero {
    background: var(--navy);
    padding: 2.5rem 0 3rem;
    position: relative;
    overflow: hidden;
}
.course-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url('{{ $course->thumbnail ? Storage::url($course->thumbnail) : "" }}')
                center/cover no-repeat;
    opacity: .12;
    filter: blur(8px);
}
.hero-inner {
    position: relative; z-index: 1;
    max-width: 1100px; margin: 0 auto;
    padding: 0 1.5rem;
    display: grid; grid-template-columns: 1fr 340px;
    gap: 2rem; align-items: start;
}
@media(max-width:900px){
    .hero-inner { grid-template-columns: 1fr; }
    .hero-side  { display: none; }
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(108,99,255,.25); color: #c0bfe0;
    font-size: .73rem; font-weight: 600;
    padding: .25rem .7rem; border-radius: 5px;
    margin-bottom: .75rem;
}
.hero-title {
    font-size: 1.7rem; font-weight: 700;
    color: #fff; line-height: 1.3;
    margin-bottom: .75rem;
}
.hero-meta {
    display: flex; flex-wrap: wrap; gap: .5rem 1.25rem;
    margin-bottom: 1rem; align-items: center;
}
.hero-meta-item {
    display: flex; align-items: center; gap: 5px;
    font-size: .83rem; color: #c0bfe0;
}
.hero-stars { color: #f39c12; font-size: .88rem; }
.hero-img {
    width: 100%; border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,.4);
    aspect-ratio: 16/9; object-fit: cover;
}
.hero-img-placeholder {
    width: 100%; aspect-ratio: 16/9;
    background: linear-gradient(135deg, #2d2d4e, #1a1a2e);
    border-radius: 12px; display: flex;
    align-items: center; justify-content: center;
    color: #534ab7; font-size: 4rem;
    box-shadow: 0 20px 60px rgba(0,0,0,.4);
}

/* ── Layout principal ──────────────────────────────── */
.show-layout {
    max-width: 1100px; margin: 0 auto;
    padding: 2rem 1.5rem;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2rem; align-items: start;
}
@media(max-width:900px){
    .show-layout { grid-template-columns:1fr; }
}

/* ── Onglets ───────────────────────────────────────── */
.tabs-nav {
    display: flex; gap: 0;
    border-bottom: 1px solid #e8e6e0;
    margin-bottom: 1.5rem;
}
.tab-btn {
    padding: .65rem 1.1rem; border: none;
    background: transparent; font-size: .88rem;
    font-weight: 500; color: #888;
    border-bottom: 2.5px solid transparent;
    cursor: pointer; transition: all .15s;
    position: relative; top: 1px;
}
.tab-btn:hover { color: var(--brand); }
.tab-btn.active {
    color: var(--brand); font-weight: 600;
    border-bottom-color: var(--brand);
}
.tab-panel { display: none; }
.tab-panel.active { display: block; animation: fadeUp .3s ease both; }

/* ── Section titles ────────────────────────────────── */
.sec-title {
    font-size: .92rem; font-weight: 700;
    color: #1a1a2e; margin-bottom: 1rem;
    display: flex; align-items: center; gap: 8px;
}
.sec-title i { color: var(--brand); }

/* ── Lesson list ───────────────────────────────────── */
.lesson-list {
    border: 1px solid #e8e6e0;
    border-radius: 12px; overflow: hidden;
}
.lesson-row {
    display: flex; align-items: center; gap: 10px;
    padding: .75rem 1rem;
    border-bottom: 1px solid #f4f3ef;
    font-size: .85rem; color: #444;
    transition: background .12s; text-decoration: none;
}
.lesson-row:last-child { border-bottom: none; }
.lesson-row:hover { background: #f8f7f4; color: #1a1a2e; }
.lesson-row.locked { color: #bbb; cursor: default; }
.l-num {
    width: 24px; flex-shrink: 0;
    font-size: .73rem; color: #ccc;
    text-align: center;
}
.l-dur {
    margin-left: auto; flex-shrink: 0;
    font-size: .73rem; color: #bbb;
}

/* ── Sidebar sticky ────────────────────────────────── */
.course-sidebar {
    position: sticky; top: calc(var(--nav-h) + 16px);
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.sidebar-price {
    padding: 1.2rem 1.25rem 1rem;
    border-bottom: 1px solid #f0efe9;
}
.price-big {
    font-size: 2rem; font-weight: 700;
    color: var(--brand); line-height: 1;
}
.price-free {
    font-size: 1.8rem; font-weight: 700;
    color: #198754;
}
.sidebar-cta { padding: 1rem 1.25rem; border-bottom: 1px solid #f0efe9; }
.btn-enroll {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; width: 100%; padding: .85rem;
    border: none; border-radius: 10px;
    font-size: .95rem; font-weight: 600;
    cursor: pointer; transition: all .15s; text-decoration: none;
}
.btn-enroll:hover { transform: scale(1.02); }
.btn-purple { background: var(--brand); color: #fff; }
.btn-purple:hover { background: #5a52d5; color: #fff; }
.btn-green  { background: #198754; color: #fff; }
.btn-green:hover { background: #157347; color: #fff; }

/* ── Progression ───────────────────────────────────── */
.prog-row {
    display: flex; justify-content: space-between;
    font-size: .76rem; margin-bottom: 5px; color: #888;
}
.prog-bar {
    height: 6px; border-radius: 999px;
    background: #e8e6e0; overflow: hidden; margin-bottom: 1rem;
}
.prog-fill {
    height: 100%; border-radius: 999px;
    background: var(--brand); transition: width .6s ease;
}
.prog-fill.done { background: #198754; }

/* Sidebar infos */
.sidebar-facts { padding: 1rem 1.25rem; }
.fact-row {
    display: flex; align-items: center; gap: 8px;
    font-size: .83rem; color: #555; padding: .3rem 0;
}
.fact-row i { color: var(--brand); width: 16px; }

/* ── Formateur card ────────────────────────────────── */
.trainer-card {
    padding: 1rem 1.25rem;
    border-top: 1px solid #f0efe9;
}
.trainer-av {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--brand); color: #fff;
    font-size: .84rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* ── Review card ───────────────────────────────────── */
.rv {
    padding: 1rem; border-radius: 10px;
    border: 1px solid #e8e6e0;
    background: #fff; margin-bottom: .75rem;
}
.rv-av {
    width: 34px; height: 34px; border-radius: 50%;
    background: #eeedfe; color: #534ab7;
    font-size: .72rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* ── Discussion placeholder ────────────────────────── */
.discuss-ph {
    text-align: center; padding: 2.5rem;
    background: #f8f7f4; border-radius: 12px;
    border: 1px dashed #d3d1c7;
}
.discuss-ph i { font-size: 2.5rem; color: #d3d1c7; }

/* ── Brouillon alert ───────────────────────────────── */
.draft-alert {
    background: #fff3cd; border-left: 3px solid #ffc107;
    border-radius: 8px; padding: .6rem 1rem;
    font-size: .82rem; color: #856404;
    margin-bottom: 1rem;
}
</style>

{{-- ── Hero section ──────────────────────────────────── --}}
<div class="course-hero">
    <div class="hero-inner">
        <div>
            {{-- Fil d'Ariane --}}
            <div style="font-size:.78rem;color:#9b9bc4;margin-bottom:.75rem">
                <a href="{{ route('courses.index') }}"
                   style="color:#9b9bc4;text-decoration:none">Catalogue</a>
                <span class="mx-1">/</span>
                <span>{{ Str::limit($course->title, 40) }}</span>
            </div>

            <div class="hero-badge">
                <i class="bi bi-folder2"></i>
                {{ $course->category->name }}
            </div>

            <h1 class="hero-title">{{ $course->title }}</h1>

            @if($course->description)
            <p style="color:#c0bfe0;font-size:.88rem;line-height:1.6;margin-bottom:1rem;
                      max-width:580px">
                {{ Str::limit(strip_tags($course->description), 160) }}
            </p>
            @endif

            <div class="hero-meta">
                @php $avg = $course->averageRating(); @endphp
                @if($avg > 0)
                <div class="hero-meta-item">
                    <span class="hero-stars">
                        @for($i=1;$i<=5;$i++)
                            {{ $i <= round($avg) ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span style="color:#fff;font-weight:600">{{ $avg }}</span>
                    <span>({{ $course->reviews()->count() }} avis)</span>
                </div>
                @endif
                <div class="hero-meta-item">
                    <i class="bi bi-people"></i>
                    {{ $course->students()->count() }} inscrits
                </div>
                <div class="hero-meta-item">
                    <i class="bi bi-collection-play"></i>
                    {{ $course->lessons->count() }} leçons
                </div>
                <div class="hero-meta-item">
                    <i class="bi bi-person"></i>
                    {{ $course->formateur->name }}
                </div>
            </div>
        </div>

        {{-- Image hero --}}
        <div class="hero-side">
            @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}"
                     class="hero-img" alt="{{ $course->title }}">
            @else
                <div class="hero-img-placeholder">
                    <i class="bi bi-play-circle"></i>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── Corps ──────────────────────────────────────────── --}}
<div class="show-layout">

    {{-- ── Colonne gauche ────────────────────────────── --}}
    <div>

        {{-- Flash --}}
        @foreach(['success'=>'s','error'=>'e','info'=>'i'] as $t=>$c)
            @if(session($t))
                <script>
                    document.addEventListener('DOMContentLoaded',()=>
                        showToast('{{ addslashes(session($t)) }}','{{ $c }}'));
                </script>
            @endif
        @endforeach

        {{-- Brouillon --}}
        @if(!$course->is_published)
        <div class="draft-alert">
            <i class="bi bi-eye-slash me-1"></i>
            Ce cours est en <strong>brouillon</strong> — non visible dans le catalogue.
        </div>
        @endif

        {{-- Onglets --}}
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="showTab('contenu', this)">
                <i class="bi bi-list-check me-1"></i>Contenu
            </button>
            <button class="tab-btn" onclick="showTab('avis', this)">
                <i class="bi bi-star me-1"></i>Avis
                <span style="font-size:.72rem;background:#eeedfe;color:#534ab7;
                             padding:.1rem .4rem;border-radius:4px;margin-left:4px">
                    {{ $course->reviews()->count() }}
                </span>
            </button>
            <button class="tab-btn" onclick="showTab('discussion', this)">
                <i class="bi bi-chat me-1"></i>Discussion
            </button>
        </div>

        {{-- Onglet Contenu --}}
        <div class="tab-panel active" id="tab-contenu">

            {{-- Description complète --}}
            @if($course->description)
            <div class="mb-4">
                <div class="sec-title">
                    <i class="bi bi-info-circle"></i>À propos de ce cours
                </div>
                <div style="font-size:.88rem;color:#555;line-height:1.85;
                            white-space:pre-line">{{ $course->description }}</div>
            </div>
            @endif

            {{-- Leçons --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="sec-title mb-0">
                        <i class="bi bi-collection-play"></i>Programme
                        <span style="font-size:.78rem;color:#aaa;font-weight:400">
                            ({{ $course->lessons->count() }} leçons)
                        </span>
                    </div>
                    @auth
                    @if(auth()->user()->isAdmin() || auth()->id() === $course->user_id)
                    <a href="{{ route('lessons.create', $course) }}"
                       class="btn btn-sm"
                       style="background:var(--brand);color:#fff;border-radius:7px;
                              border:none;font-size:.78rem">
                        <i class="bi bi-plus-lg"></i> Ajouter
                    </a>
                    @endif
                    @endauth
                </div>

                @if($course->lessons->isNotEmpty())
                @php
                    $canSee = auth()->check() && (
                        auth()->user()->isAdmin()
                        || auth()->id() === $course->user_id
                        || auth()->user()->isEnrolledIn($course)
                    );
                    $canManage = auth()->check() && (
                        auth()->user()->isAdmin()
                        || auth()->id() === $course->user_id
                    );
                @endphp
                <div class="lesson-list">
                    @foreach($course->lessons as $lesson)
                    @if($canSee)
                    <a href="{{ route('lessons.show', [$course, $lesson]) }}"
                       class="lesson-row">
                    @else
                    <div class="lesson-row locked">
                    @endif
                        <span class="l-num">{{ $loop->iteration }}</span>
                        <i class="bi bi-{{ $canSee ? 'play-circle' : 'lock' }}"
                           style="color:{{ $canSee ? 'var(--brand)' : '#d3d1c7' }};
                                  font-size:.88rem;flex-shrink:0"></i>
                        <span style="flex:1">{{ $lesson->title }}</span>
                        @if($lesson->duration)
                        <span class="l-dur">{{ $lesson->duration }}min</span>
                        @endif
                        @if($canManage)
                        <a href="{{ route('lessons.edit',[$course,$lesson]) }}"
                           class="btn btn-sm btn-outline-secondary ms-1"
                           style="border-radius:6px;font-size:.72rem;padding:.15rem .45rem"
                           onclick="event.stopPropagation()">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endif
                    @if($canSee) </a> @else </div> @endif
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted"
                     style="background:#f8f7f4;border-radius:10px;font-size:.88rem">
                    <i class="bi bi-collection-play"
                       style="font-size:2rem;color:#d3d1c7;display:block;margin-bottom:.5rem">
                    </i>
                    Aucune leçon pour l'instant.
                </div>
                @endif
            </div>

            {{-- Quiz --}}
            @php $quiz = $course->quizzes()->where('is_active', true)->first(); @endphp
            @if($quiz)
            <div class="mb-4">
                <div class="sec-title">
                    <i class="bi bi-patch-question"></i>Quiz du cours
                </div>
                @auth
                @if(auth()->user()->isEnrolledIn($course) || auth()->user()->isAdmin() || auth()->id() === $course->user_id)
                @php $qRes = $quiz->resultForUser(auth()->user()); @endphp
                <div style="background:#f8f7f4;border-radius:10px;padding:1rem 1.25rem;
                            border:1px solid #e8e6e0;display:flex;
                            justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
                    <div>
                        <div style="font-size:.88rem;font-weight:600;color:#1a1a2e">
                            {{ $quiz->title }}
                        </div>
                        <div style="font-size:.76rem;color:#aaa">
                            {{ $quiz->questions()->count() }} questions
                            · Score requis : {{ $quiz->pass_score }}%
                            @if($qRes)
                                · Dernier score : <strong
                                  style="color:{{ $qRes->passed ? '#198754' : '#dc3545' }}">
                                  {{ $qRes->score }}%</strong>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('quiz.show', $course) }}"
                       class="btn btn-sm fw-semibold"
                       style="background:var(--brand);color:#fff;
                              border-radius:8px;border:none;font-size:.82rem">
                        <i class="bi bi-patch-question me-1"></i>
                        {{ $qRes ? 'Retenter' : 'Passer le quiz' }}
                    </a>
                </div>
                @endif
                @endauth
            </div>
            @endif

        </div>

        {{-- Onglet Avis --}}
        <div class="tab-panel" id="tab-avis">

            @php $avg = $course->averageRating(); @endphp
            @if($avg > 0)
            <div style="background:#f8f7f4;border-radius:12px;padding:1.25rem;
                        margin-bottom:1.25rem;display:flex;align-items:center;gap:1.5rem">
                <div style="text-align:center;flex-shrink:0">
                    <div style="font-size:3rem;font-weight:700;color:var(--navy);
                                line-height:1">{{ $avg }}</div>
                    <div style="color:#f39c12;font-size:1.1rem">
                        @for($i=1;$i<=5;$i++)
                            {{ $i <= round($avg) ? '★' : '☆' }}
                        @endfor
                    </div>
                    <div style="font-size:.74rem;color:#aaa;margin-top:2px">
                        Note globale
                    </div>
                </div>
                <div style="flex:1">
                    @foreach([5,4,3,2,1] as $star)
                    @php $count = $course->reviews()->where('rating',$star)->count();
                         $pct = $course->reviews()->count() > 0
                            ? round($count/$course->reviews()->count()*100) : 0;
                    @endphp
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
                        <span style="font-size:.75rem;color:#aaa;width:8px">{{ $star }}</span>
                        <i class="bi bi-star-fill" style="color:#f39c12;font-size:.7rem"></i>
                        <div style="flex:1;height:6px;background:#e8e6e0;border-radius:999px;overflow:hidden">
                            <div style="width:{{ $pct }}%;height:100%;background:#f39c12;border-radius:999px"></div>
                        </div>
                        <span style="font-size:.73rem;color:#aaa;width:28px">{{ $pct }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Formulaire avis --}}
            @auth
            @if(auth()->user()->isEtudiant() && auth()->user()->isEnrolledIn($course))
            @php $hasRev = \App\Models\Review::where('user_id',auth()->id())->where('course_id',$course->id)->exists(); @endphp
            @if(!$hasRev)
            <div style="background:#f8f7f4;border-radius:12px;padding:1.25rem;margin-bottom:1.25rem;border:1px solid #e8e6e0">
                <div class="sec-title"><i class="bi bi-pencil-square"></i>Laisser un avis</div>
                <form method="POST" action="{{ route('reviews.store', $course) }}">
                    @csrf
                    <div class="mb-3">
                        <div id="stars" style="display:flex;gap:8px">
                            @for($i=1;$i<=5;$i++)
                            <label for="r{{ $i }}"
                                   style="font-size:1.8rem;cursor:pointer;color:#d3d1c7;transition:color .1s">
                                ★
                                <input type="radio" name="rating" id="r{{ $i }}"
                                       value="{{ $i }}" class="d-none" required>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <textarea name="comment" class="form-control mb-3" rows="3"
                              placeholder="Votre expérience..."
                              style="border-radius:9px;font-size:.87rem"></textarea>
                    <button type="submit" class="btn btn-sm fw-semibold px-4"
                            style="background:var(--brand);color:#fff;border-radius:8px;border:none">
                        Publier
                    </button>
                </form>
            </div>
            @endif
            @endif
            @endauth

            {{-- Liste avis --}}
            @forelse($course->reviews()->with('user')->latest()->get() as $rv)
            <div class="rv">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="display:flex;gap:10px;align-items:center;margin-bottom:.5rem">
                        <div class="rv-av">{{ strtoupper(substr($rv->user->name,0,2)) }}</div>
                        <div>
                            <div style="font-size:.86rem;font-weight:600;color:#1a1a2e">
                                {{ $rv->user->name }}
                            </div>
                            <div style="color:#f39c12;font-size:.82rem">
                                {{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <small style="color:#bbb;font-size:.72rem">{{ $rv->created_at->diffForHumans() }}</small>
                        @auth
                        @if(auth()->id()===$rv->user_id||auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('reviews.destroy',$rv) }}"
                              onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    style="border-radius:5px;font-size:.7rem;padding:.1rem .35rem">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
                @if($rv->comment)
                <p style="font-size:.84rem;color:#555;margin:0;line-height:1.6">{{ $rv->comment }}</p>
                @endif
            </div>
            @empty
            <div class="text-center py-4 text-muted" style="font-size:.88rem">
                <i class="bi bi-star" style="font-size:2rem;color:#d3d1c7;display:block;margin-bottom:.5rem"></i>
                Aucun avis pour ce cours.
            </div>
            @endforelse
        </div>

        {{-- Onglet Discussion --}}
        <div class="tab-panel" id="tab-discussion">
            <div class="discuss-ph">
                <i class="bi bi-chat-dots" style="font-size:2.5rem;color:#d3d1c7;display:block;margin-bottom:.75rem"></i>
                <div style="font-size:.9rem;font-weight:600;color:#1a1a2e;margin-bottom:.35rem">
                    Forum de discussion
                </div>
                <p style="font-size:.83rem;color:#aaa;max-width:300px;margin:0 auto .75rem">
                    La section discussion sera disponible dans une prochaine mise à jour.
                </p>
                <span style="background:#eeedfe;color:#534ab7;font-size:.75rem;
                             padding:.3rem .75rem;border-radius:6px;font-weight:500">
                    Bientôt disponible
                </span>
            </div>
        </div>

    </div>

    {{-- ── Sidebar ──────────────────────────────────── --}}
    <div>
        <div class="course-sidebar">

            {{-- Prix --}}
            <div class="sidebar-price">
                @if($course->price > 0)
                    <div class="price-big">
                        {{ number_format($course->price, 2, ',', ' ') }} €
                    </div>
                @else
                    <div class="price-free">
                        <i class="bi bi-gift me-1"></i>Gratuit
                    </div>
                @endif
                <div style="font-size:.74rem;color:#aaa;margin-top:4px">
                    <i class="bi bi-shield-check me-1 text-success"></i>Accès à vie
                </div>
            </div>

            {{-- CTA --}}
            <div class="sidebar-cta">
                @auth

                @if(auth()->user()->isEtudiant())

                    @if(auth()->user()->isEnrolledIn($course))
                    {{-- Progression --}}
                    @php
                        $prog = auth()->user()->enrolledCourses()
                            ->where('course_id',$course->id)
                            ->first()?->pivot->progress ?? 0;
                    @endphp
                    <div class="prog-row">
                        <span>Progression</span>
                        <span style="color:{{ $prog==100?'#198754':'var(--brand)' }};font-weight:600">
                            {{ $prog }}%
                        </span>
                    </div>
                    <div class="prog-bar">
                        <div class="prog-fill {{ $prog==100?'done':'' }}"
                             style="width:{{ $prog }}%"></div>
                    </div>

                    @if($course->lessons->isNotEmpty())
                    <a href="{{ route('lessons.show',[$course,$course->lessons->first()]) }}"
                       class="btn-enroll btn-green mb-2">
                        <i class="bi bi-play-fill"></i>
                        Continuer le cours
                    </a>
                    @endif

                    @if($prog >= 100)
                    <a href="{{ route('certificate.generate', $course) }}"
                       class="btn-enroll"
                       style="background:#faeeda;color:#633806;margin-top:6px">
                        <i class="bi bi-award me-1"></i>Télécharger le certificat
                    </a>
                    @endif

                    <form method="POST"
                          action="{{ route('enrollments.destroy',$course) }}"
                          class="mt-2"
                          onsubmit="return confirm('Se désinscrire ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger w-100"
                                style="border-radius:7px;font-size:.78rem">
                            Se désinscrire
                        </button>
                    </form>

                    @else
                    {{-- Pas encore inscrit --}}
                    @if($course->price > 0)
                    <a href="{{ route('payment.show', $course) }}"
                       class="btn-enroll btn-purple">
                        <i class="bi bi-credit-card"></i>
                        Acheter — {{ number_format($course->price,2,',') }} €
                    </a>
                    @else
                    <form method="POST" action="{{ route('enrollments.store', $course) }}">
                        @csrf
                        <button type="submit" class="btn-enroll btn-purple">
                            <i class="bi bi-rocket-takeoff"></i>
                            S'inscrire gratuitement
                        </button>
                    </form>
                    @endif
                    @endif

                @elseif(auth()->user()->isAdmin() || auth()->id() === $course->user_id)
                <a href="{{ route('lessons.create', $course) }}"
                   class="btn-enroll btn-purple">
                    <i class="bi bi-plus-lg"></i>Ajouter une leçon
                </a>
                @endif

                @else
                <a href="{{ route('login') }}" class="btn-enroll btn-purple">
                    <i class="bi bi-person"></i>Se connecter pour s'inscrire
                </a>
                @endauth
            </div>

            {{-- Infos rapides --}}
            <div class="sidebar-facts">
                <div class="fact-row">
                    <i class="bi bi-collection-play"></i>
                    <strong>{{ $course->lessons->count() }}</strong> leçons
                </div>
                <div class="fact-row">
                    <i class="bi bi-people"></i>
                    <strong>{{ $course->students()->count() }}</strong> inscrits
                </div>
                <div class="fact-row">
                    <i class="bi bi-folder2"></i>
                    {{ $course->category->name }}
                </div>
                <div class="fact-row">
                    <i class="bi bi-calendar3"></i>
                    Mis à jour le {{ $course->updated_at->format('d/m/Y') }}
                </div>
            </div>

            {{-- Formateur --}}
            <div class="trainer-card">
                <div style="font-size:.72rem;color:#aaa;font-weight:600;
                            text-transform:uppercase;letter-spacing:.5px;margin-bottom:.6rem">
                    Formateur
                </div>
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="trainer-av">
                        {{ strtoupper(substr($course->formateur->name,0,2)) }}
                    </div>
                    <div>
                        <div style="font-size:.88rem;font-weight:600;color:#1a1a2e">
                            {{ $course->formateur->name }}
                        </div>
                        <div style="font-size:.74rem;color:#aaa">Formateur certifié</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// ── Onglets ─────────────────────────────────────────
function showTab(id, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}

// ── Étoiles interactives ────────────────────────────
const starLabels = document.querySelectorAll('#stars label');
starLabels.forEach((lbl, idx) => {
    lbl.addEventListener('mouseover', () => {
        starLabels.forEach((l, i) =>
            l.style.color = i <= idx ? '#f39c12' : '#d3d1c7');
    });
    lbl.addEventListener('mouseout', () => {
        const checked = document.querySelector('input[name="rating"]:checked');
        starLabels.forEach((l, i) =>
            l.style.color = checked && i < parseInt(checked.value)
                ? '#f39c12' : '#d3d1c7');
    });
    lbl.addEventListener('click', () =>
        starLabels.forEach((l, i) =>
            l.style.color = i <= idx ? '#f39c12' : '#d3d1c7'));
});
</script>
@endsection