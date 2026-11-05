@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<style>
/* ════════════════════════════════════════════════════
   DESIGN SYSTEM — Dashboard SaaS
════════════════════════════════════════════════════ */
:root {
    --brand:      #6c63ff;
    --brand-dk:   #5a52d5;
    --brand-lt:   #eeedfe;
    --navy:       #1a1a2e;
    --surface:    #ffffff;
    --bg:         #f4f3f8;
    --border:     #ebe9f1;
    --text-1:     #1a1a2e;
    --text-2:     #6b6882;
    --text-3:     #a8a5b8;
    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  16px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    --shadow-lg:  0 12px 40px rgba(0,0,0,.1),  0 4px 12px rgba(0,0,0,.05);
    --transition: all .2s cubic-bezier(.4,0,.2,1);
}

body { background: var(--bg) !important; }

/* ── Layout ──────────────────────────────────────── */
.db-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 2rem 1.5rem 3rem;
}

/* ── Header ──────────────────────────────────────── */
.db-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}
.db-greeting {
    font-size: .8rem;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--brand);
    margin-bottom: 3px;
}
.db-title {
    font-size: 1.65rem;
    font-weight: 750;
    color: var(--text-1);
    margin: 0;
    letter-spacing: -.3px;
}
.db-date {
    font-size: .82rem;
    color: var(--text-3);
    margin-top: 3px;
}
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--brand);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    padding: .62rem 1.3rem;
    font-size: .87rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 4px 14px rgba(108,99,255,.35);
}
.btn-action:hover {
    background: var(--brand-dk);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(108,99,255,.4);
}

/* ── KPI Grid ────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media(max-width:1024px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px)  { .kpi-grid { grid-template-columns: repeat(2,1fr); gap:.65rem; } }

.kpi-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.3rem;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    cursor: default;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: transparent;
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--kpi-color, var(--brand));
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
}
.kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: .9rem;
}
.kpi-icon {
    width: 44px; height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    background: var(--kpi-bg, var(--brand-lt));
    color: var(--kpi-color, var(--brand));
    flex-shrink: 0;
}
.kpi-trend {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: .7rem;
    font-weight: 600;
    padding: .2rem .5rem;
    border-radius: 5px;
}
.kpi-trend.up   { background: #e1f5ee; color: #0f6e56; }
.kpi-trend.neu  { background: #f4f3f8; color: var(--text-3); }
.kpi-label {
    font-size: .73rem;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: .3rem;
}
.kpi-value {
    font-size: 2rem;
    font-weight: 750;
    color: var(--text-1);
    line-height: 1;
    letter-spacing: -.5px;
}
.kpi-sub {
    font-size: .73rem;
    color: var(--text-3);
    margin-top: 3px;
}

/* ── Content grid ────────────────────────────────── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
@media(max-width:1024px) { .content-grid { grid-template-columns: 1fr; } }

.content-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media(max-width:800px) { .content-grid-3 { grid-template-columns: 1fr; } }

/* ── Panel (card générique) ──────────────────────── */
.panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: box-shadow .2s;
}
.panel:hover { box-shadow: var(--shadow-md); }
.panel-hd {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.1rem 1.3rem;
    border-bottom: 1px solid var(--border);
}
.panel-title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--text-1);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 7px;
}
.panel-title i { color: var(--brand); }
.panel-badge {
    background: var(--brand-lt);
    color: var(--brand);
    font-size: .7rem;
    font-weight: 700;
    padding: .2rem .55rem;
    border-radius: 6px;
}
.panel-body { padding: 1.1rem 1.3rem; }
.panel-body-flush { padding: 0; }

/* ── Courses table ───────────────────────────────── */
.courses-tbl {
    width: 100%;
    border-collapse: collapse;
}
.courses-tbl thead th {
    background: var(--bg);
    font-size: .69rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--text-3);
    padding: .65rem 1.1rem;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.courses-tbl tbody tr {
    border-bottom: 1px solid #f6f5fb;
    transition: var(--transition);
}
.courses-tbl tbody tr:last-child { border: none; }
.courses-tbl tbody tr:hover { background: #faf9fd; }
.courses-tbl td {
    padding: .85rem 1.1rem;
    vertical-align: middle;
}
.tbl-course-name {
    font-weight: 600;
    font-size: .87rem;
    color: var(--text-1);
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tbl-course-cat {
    font-size: .72rem;
    color: var(--text-3);
    margin-top: 1px;
}
.tbl-thumb {
    width: 44px; height: 44px;
    border-radius: var(--radius-sm);
    object-fit: cover;
    flex-shrink: 0;
}
.tbl-thumb-ph {
    width: 44px; height: 44px;
    border-radius: var(--radius-sm);
    background: var(--brand-lt);
    display: flex; align-items: center; justify-content: center;
    color: var(--brand); font-size: 1.1rem; flex-shrink: 0;
}

/* ── Pill badges ─────────────────────────────────── */
.pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: .22rem .6rem;
    border-radius: 6px;
    font-size: .71rem;
    font-weight: 700;
    letter-spacing: .2px;
}
.pill-green   { background: #e1f5ee; color: #0f6e56; }
.pill-amber   { background: #faeeda; color: #854f0b; }
.pill-purple  { background: var(--brand-lt); color: var(--brand); }
.pill-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* ── Action buttons ──────────────────────────────── */
.act-btn {
    width: 30px; height: 30px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
}
.act-btn:hover {
    border-color: var(--brand);
    color: var(--brand);
    background: var(--brand-lt);
    transform: scale(1.08);
}
.act-btn.solid {
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
    box-shadow: 0 2px 8px rgba(108,99,255,.3);
}
.act-btn.solid:hover {
    background: var(--brand-dk);
    transform: scale(1.08);
}

/* ── Chart ───────────────────────────────────────── */
.chart-wrap { padding: 1.1rem; }

/* ── Activity feed ───────────────────────────────── */
.act-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: .8rem 1.1rem;
    border-bottom: 1px solid #f6f5fb;
    transition: background .15s;
}
.act-item:last-child { border: none; }
.act-item:hover { background: #faf9fd; }
.act-icon {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.act-text {
    font-size: .84rem;
    font-weight: 600;
    color: var(--text-1);
    line-height: 1.3;
}
.act-sub {
    font-size: .76rem;
    color: var(--text-3);
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.act-time {
    font-size: .71rem;
    color: var(--text-3);
    margin-left: auto;
    white-space: nowrap;
    flex-shrink: 0;
}

/* ── Performance per course ──────────────────────── */
.perf-row {
    padding: .85rem 1.1rem;
    border-bottom: 1px solid #f6f5fb;
    transition: background .15s;
}
.perf-row:last-child { border: none; }
.perf-row:hover { background: #faf9fd; }
.perf-title {
    font-size: .84rem;
    font-weight: 600;
    color: var(--text-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    margin-bottom: 5px;
}
.perf-bar {
    height: 5px;
    background: #ebe9f1;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 3px;
}
.perf-fill {
    height: 100%;
    border-radius: 999px;
    background: var(--brand);
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.perf-meta {
    display: flex;
    justify-content: space-between;
    font-size: .71rem;
    color: var(--text-3);
}

/* ── Empty state ─────────────────────────────────── */
.empty-dash {
    text-align: center;
    padding: 5rem 2rem;
}
.empty-dash-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: var(--brand-lt);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--brand);
    margin: 0 auto 1.25rem;
}

/* ── Animations ──────────────────────────────────── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim { opacity: 0; }
.anim.visible { animation: fadeUp .5s ease forwards; }

@keyframes countUp {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<div class="db-wrap">

{{-- ══ HEADER ══════════════════════════════════════ --}}
<div class="db-header anim">
    <div>
        <div class="db-greeting">
            <i class="bi bi-lightning-charge me-1"></i>Espace formateur
        </div>
        <h1 class="db-title">
            Bonjour, {{ auth()->user()->name }} 👋
        </h1>
        <p class="db-date">
            {{ now()->isoFormat('dddd D MMMM YYYY') }}
        </p>
    </div>
    <a href="{{ route('courses.create') }}" class="btn-action">
        <i class="bi bi-plus-lg"></i>Nouveau cours
    </a>
</div>

@if($courses->isEmpty())
{{-- ══ ÉTAT VIDE ════════════════════════════════════ --}}
<div class="panel anim" style="animation-delay:.1s">
    <div class="empty-dash">
        <div class="empty-dash-icon">
            <i class="bi bi-collection-play"></i>
        </div>
        <h2 style="font-size:1.2rem;font-weight:700;color:var(--text-1);margin-bottom:.4rem">
            Commencez à créer vos cours
        </h2>
        <p style="font-size:.88rem;color:var(--text-3);max-width:340px;
                  margin:0 auto 1.5rem;line-height:1.6">
            Vous n'avez aucun cours pour l'instant. Créez votre premier cours
            et commencez à partager vos connaissances.
        </p>
        <a href="{{ route('courses.create') }}" class="btn-action">
            <i class="bi bi-plus-lg"></i>Créer mon premier cours
        </a>
    </div>
</div>

@else
{{-- ══ KPI CARDS ════════════════════════════════════ --}}
<div class="kpi-grid">

    {{-- Cours --}}
    <div class="kpi-card anim"
         style="--kpi-color:#6c63ff;--kpi-bg:#eeedfe;animation-delay:.05s">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="bi bi-collection-play"></i></div>
            <span class="kpi-trend up">
                <i class="bi bi-arrow-up-short"></i>actifs
            </span>
        </div>
        <div class="kpi-label">Cours créés</div>
        <div class="kpi-value" data-count="{{ $totalCourses }}">0</div>
        <div class="kpi-sub">
            {{ $published }} publiés · {{ $drafts }} brouillons
        </div>
    </div>

    {{-- Étudiants --}}
    <div class="kpi-card anim"
         style="--kpi-color:#0f6e56;--kpi-bg:#e1f5ee;animation-delay:.1s">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="bi bi-people"></i></div>
            <span class="kpi-trend up">
                <i class="bi bi-graph-up"></i>croissance
            </span>
        </div>
        <div class="kpi-label">Étudiants</div>
        <div class="kpi-value" data-count="{{ $totalStudents }}">0</div>
        <div class="kpi-sub">inscriptions totales</div>
    </div>

    {{-- Revenus --}}
    <div class="kpi-card anim"
         style="--kpi-color:#854f0b;--kpi-bg:#faeeda;animation-delay:.15s">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="bi bi-cash-stack"></i></div>
            <span class="kpi-trend neu">estimé</span>
        </div>
        <div class="kpi-label">Paiements simulés estimés</div>
        <div class="kpi-value">
            {{ number_format($revenueEst, 0, ',', ' ') }} €
        </div>
        <div class="kpi-sub">prix actuel × inscriptions payées · simulation</div>
    </div>

    {{-- Note --}}
    <div class="kpi-card anim"
         style="--kpi-color:#993c1d;--kpi-bg:#faece7;animation-delay:.2s">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="bi bi-star-half"></i></div>
            @if($avgRating >= 4)
                <span class="kpi-trend up"><i class="bi bi-emoji-smile"></i>excellent</span>
            @elseif($avgRating > 0)
                <span class="kpi-trend neu">sur 5</span>
            @else
                <span class="kpi-trend neu">aucun avis</span>
            @endif
        </div>
        <div class="kpi-label">Note moyenne</div>
        <div class="kpi-value">
            @if($avgRating > 0)
                {{ $avgRating }}
                <span style="font-size:1.2rem;color:#f39c12">★</span>
            @else
                <span style="font-size:1.1rem;color:var(--text-3)">—</span>
            @endif
        </div>
        <div class="kpi-sub">{{ $totalLessons }} leçons créées</div>
    </div>

</div>

{{-- ══ GRAPHIQUES ══════════════════════════════════ --}}
@if($chartLabels->isNotEmpty())
<div class="content-grid-3 anim" style="animation-delay:.25s;margin-bottom:1.25rem">

    {{-- Chart étudiants --}}
    <div class="panel">
        <div class="panel-hd">
            <h2 class="panel-title">
                <i class="bi bi-bar-chart-line"></i>Inscrits par cours
            </h2>
            <span class="panel-badge">Top {{ $chartLabels->count() }}</span>
        </div>
        <div class="chart-wrap">
            <canvas id="chart-students" height="210"></canvas>
        </div>
    </div>

    {{-- Chart revenus --}}
    <div class="panel">
        <div class="panel-hd">
            <h2 class="panel-title">
                <i class="bi bi-graph-up-arrow"></i>Paiements simulés estimés par cours
            </h2>
            <span class="panel-badge">€</span>
        </div>
        <div class="chart-wrap">
            <canvas id="chart-revenue" height="210"></canvas>
        </div>
    </div>

</div>
@endif

{{-- ══ TABLE + ACTIVITÉ ════════════════════════════ --}}
<div class="content-grid anim" style="animation-delay:.3s">

    {{-- Table des cours --}}
    <div class="panel">
        <div class="panel-hd">
            <h2 class="panel-title">
                <i class="bi bi-table"></i>Mes cours
            </h2>
            <span class="panel-badge">{{ $totalCourses }}</span>
        </div>
        <div class="panel-body-flush">
            <div style="overflow-x:auto">
                <table class="courses-tbl">
                    <thead>
                        <tr>
                            <th style="padding-left:1.3rem">Cours</th>
                            <th>Statut</th>
                            <th class="text-center">Inscrits</th>
                            <th class="text-center">Estimation</th>
                            <th class="text-center">Note</th>
                            <th class="text-center"
                                style="padding-right:1.1rem"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            {{-- Thumbnail + titre --}}
                            <td style="padding-left:1.1rem">
                                <div style="display:flex;align-items:center;gap:10px">
                                    @if($course->thumbnail)
                                        <img src="{{ Storage::url($course->thumbnail) }}"
                                             class="tbl-thumb" alt="">
                                    @else
                                        <div class="tbl-thumb-ph">
                                            <i class="bi bi-play-circle"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="tbl-course-name">
                                            {{ $course->title }}
                                        </div>
                                        <div class="tbl-course-cat">
                                            {{ $course->category->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Statut --}}
                            <td>
                                @if($course->archived_at)
                                    <span class="pill pill-amber">
                                        <span class="pill-dot"></span>Archivé
                                    </span>
                                @elseif($course->is_published)
                                    <span class="pill pill-green">
                                        <span class="pill-dot"></span>Publié
                                    </span>
                                @else
                                    <span class="pill pill-amber">
                                        <span class="pill-dot"></span>Brouillon
                                    </span>
                                @endif
                            </td>

                            {{-- Inscrits --}}
                            <td class="text-center">
                                <span style="font-weight:700;font-size:.9rem;
                                             color:var(--text-1)">
                                    {{ $course->students_count }}
                                </span>
                            </td>

                            {{-- Revenu --}}
                            <td class="text-center">
                                @if($course->price > 0)
                                    <span style="font-weight:700;font-size:.87rem;
                                                 color:var(--brand)">
                                        {{ number_format($course->price * $course->paid_students_count,
                                            0, ',', ' ') }} €
                                    </span>
                                @else
                                    <span style="font-size:.77rem;color:var(--text-3)">
                                        Gratuit
                                    </span>
                                @endif
                            </td>

                            {{-- Note --}}
                            <td class="text-center">
                                @if($course->reviews_avg_rating)
                                    <span style="color:#f39c12;font-size:.88rem">★</span>
                                    <span style="font-size:.84rem;font-weight:600;
                                                 color:var(--text-1)">
                                        {{ number_format($course->reviews_avg_rating, 1) }}
                                    </span>
                                @else
                                    <span style="color:var(--text-3);font-size:.8rem">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center" style="padding-right:1.1rem">
                                <div style="display:flex;gap:5px;
                                            justify-content:center">
                                    <a href="{{ route('courses.show', $course) }}"
                                       class="act-btn" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($course->archived_at)
                                        <form method="POST" action="{{ route('courses.restore', $course) }}">
                                            @csrf
                                            <button type="submit" class="act-btn solid" title="Restaurer comme brouillon">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('lessons.create', $course) }}"
                                           class="act-btn solid" title="Ajouter leçon">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                        <form method="POST" action="{{ route('courses.destroy', $course) }}"
                                              onsubmit="return confirm('Archiver ce cours ? Les inscriptions et les résultats seront conservés.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act-btn" title="Archiver le cours">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Activité récente --}}
    <div class="panel">
        <div class="panel-hd">
            <h2 class="panel-title">
                <i class="bi bi-clock-history"></i>Activité récente
            </h2>
        </div>

        @if($recentActivity->isNotEmpty())
        <div class="panel-body-flush">
            @foreach($recentActivity as $act)
            <div class="act-item">
                <div class="act-icon"
                     style="background:{{ $act['bg'] }};color:{{ $act['color'] }}">
                    <i class="bi bi-{{ $act['icon'] }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="act-text">{{ $act['text'] }}</div>
                    <div class="act-sub">{{ $act['sub'] }}</div>
                </div>
                <div class="act-time">{{ $act['time'] }}</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="panel-body text-center">
            <div style="font-size:.85rem;color:var(--text-3);padding:1.5rem 0">
                <i class="bi bi-clock"
                   style="font-size:1.8rem;display:block;margin-bottom:.5rem;
                          color:#d3d1c7"></i>
                Aucune activité récente.
            </div>
        </div>
        @endif

        {{-- Performance par cours --}}
        @if($courses->isNotEmpty())
        <div class="panel-hd" style="margin-top:.5rem">
            <h2 class="panel-title">
                <i class="bi bi-bar-chart"></i>Performance
            </h2>
        </div>
        @php $maxStudents = $courses->max('students_count') ?: 1; @endphp
        @foreach($courses->take(4) as $c)
        <div class="perf-row">
            <div class="perf-title">{{ $c->title }}</div>
            <div class="perf-bar">
                <div class="perf-fill"
                     style="width:{{ round($c->students_count / $maxStudents * 100) }}%;
                            background:{{ $c->is_published ? 'var(--brand)' : '#d3d1c7' }}">
                </div>
            </div>
            <div class="perf-meta">
                <span>{{ $c->students_count }} inscrits</span>
                <span>
                    @if($c->reviews_avg_rating)
                        ★ {{ number_format($c->reviews_avg_rating, 1) }}
                    @else
                        aucun avis
                    @endif
                </span>
            </div>
        </div>
        @endforeach
        @endif
    </div>

</div>
@endif

</div>{{-- /db-wrap --}}

{{-- ══ SCRIPTS ══════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Intersection Observer → fade-in ──────────── */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, i * 40);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.anim').forEach(el => observer.observe(el));

    /* ── Count-up ─────────────────────────────────── */
    const countEls = document.querySelectorAll('[data-count]');
    const countObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el     = entry.target;
            const target = parseInt(el.dataset.count, 10);
            if (isNaN(target)) return;
            const dur    = 1200;
            const start  = performance.now();
            const run = (now) => {
                const p    = Math.min((now - start) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(ease * target).toLocaleString('fr');
                if (p < 1) requestAnimationFrame(run);
            };
            requestAnimationFrame(run);
            countObs.unobserve(el);
        });
    }, { threshold: 0.3 });
    countEls.forEach(el => countObs.observe(el));

    /* ── Palette charts ───────────────────────────── */
    const BRAND  = '#6c63ff';
    const BRAND2 = '#0f6e56';
    const defOpts = {
        responsive: true,
        animation: { duration: 900, easing: 'easeOutQuart' },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1a2e',
                titleColor: '#fff',
                bodyColor: '#c0bfe0',
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { font: { size: 11 }, color: '#a8a5b8', stepSize: 1 },
                grid: { color: 'rgba(0,0,0,.04)', drawBorder: false }
            },
            x: {
                ticks: { font: { size: 11 }, color: '#a8a5b8' },
                grid: { display: false }
            }
        }
    };

    /* ── Chart étudiants ──────────────────────────── */
    const csEl = document.getElementById('chart-students');
    if (csEl) {
        new Chart(csEl, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels->toJson() !!},
                datasets: [{
                    label: 'Inscrits',
                    data: {!! $chartStudents->toJson() !!},
                    backgroundColor: 'rgba(108,99,255,.1)',
                    borderColor: BRAND,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(108,99,255,.2)',
                }]
            },
            options: {
                ...defOpts,
                plugins: {
                    ...defOpts.plugins,
                    tooltip: {
                        ...defOpts.plugins.tooltip,
                        callbacks: {
                            label: ctx =>
                                ` ${ctx.parsed.y} étudiant${ctx.parsed.y > 1 ? 's' : ''}`
                        }
                    }
                }
            }
        });
    }

    /* ── Chart revenus ────────────────────────────── */
    const crEl = document.getElementById('chart-revenue');
    if (crEl) {
        new Chart(crEl, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels->toJson() !!},
                datasets: [{
                    label: 'Paiements simulés estimés (€)',
                    data: {!! $chartRevenues->toJson() !!},
                    backgroundColor: 'rgba(15,110,86,.1)',
                    borderColor: BRAND2,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(15,110,86,.2)',
                }]
            },
            options: {
                ...defOpts,
                plugins: {
                    ...defOpts.plugins,
                    tooltip: {
                        ...defOpts.plugins.tooltip,
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} €`
                        }
                    }
                }
            }
        });
    }

    /* ── Perf bars (entrée progressive) ──────────── */
    document.querySelectorAll('.perf-fill').forEach((bar, i) => {
        const target = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => { bar.style.width = target; }, 400 + i * 80);
    });

});
</script>

@endsection