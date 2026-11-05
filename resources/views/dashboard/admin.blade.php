@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')

<style>
/* ════════════════════════════════════════════════════
   ADMIN DASHBOARD — Design System Premium
════════════════════════════════════════════════════ */
:root {
    --brand:    #6c63ff;
    --brand-dk: #5a52d5;
    --brand-lt: #eeedfe;
    --navy:     #1a1a2e;
    --surface:  #ffffff;
    --bg:       #f4f3f8;
    --border:   #ebe9f1;
    --t1:       #1a1a2e;
    --t2:       #6b6882;
    --t3:       #a8a5b8;
    --r-sm:     8px;
    --r-md:     12px;
    --r-lg:     16px;
    --sh-sm:    0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
    --sh-md:    0 4px 16px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
    --sh-lg:    0 12px 40px rgba(0,0,0,.10),0 4px 12px rgba(0,0,0,.05);
    --tr:       all .2s cubic-bezier(.4,0,.2,1);
}
body { background: var(--bg) !important; }

/* ── Wrap ────────────────────────────────────────── */
.adm { max-width:1380px; margin:0 auto; padding:2rem 1.5rem 3rem; }

/* ── Header ──────────────────────────────────────── */
.adm-hd {
    display:flex; justify-content:space-between;
    align-items:flex-start; flex-wrap:wrap;
    gap:1rem; margin-bottom:2rem;
}
.adm-eyebrow {
    font-size:.72rem; font-weight:700; letter-spacing:.7px;
    text-transform:uppercase; color:var(--brand); margin-bottom:3px;
}
.adm-title {
    font-size:1.7rem; font-weight:750; color:var(--t1);
    letter-spacing:-.3px; margin:0;
}
.adm-sub { font-size:.82rem; color:var(--t3); margin-top:3px; }
.hd-actions { display:flex; gap:.6rem; flex-wrap:wrap; }
.btn-hd {
    display:inline-flex; align-items:center; gap:6px;
    padding:.55rem 1.1rem; border-radius:var(--r-md);
    font-size:.84rem; font-weight:600;
    text-decoration:none; transition:var(--tr); border:none; cursor:pointer;
}
.btn-hd-primary {
    background:var(--brand); color:#fff;
    box-shadow:0 4px 14px rgba(108,99,255,.3);
}
.btn-hd-primary:hover {
    background:var(--brand-dk); color:#fff;
    transform:translateY(-1px);
    box-shadow:0 6px 20px rgba(108,99,255,.4);
}
.btn-hd-ghost {
    background:var(--surface); color:var(--t2);
    border:1px solid var(--border);
}
.btn-hd-ghost:hover {
    border-color:var(--brand); color:var(--brand);
    background:var(--brand-lt);
}

/* ── KPI Grid ────────────────────────────────────── */
.kpi-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1rem; margin-bottom:1.5rem;
}
@media(max-width:1100px){ .kpi-grid{grid-template-columns:repeat(2,1fr)} }
@media(max-width:480px) { .kpi-grid{gap:.65rem} }

.kpi {
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--r-lg);
    padding:1.25rem 1.25rem 1.1rem;
    position:relative; overflow:hidden;
    transition:var(--tr); cursor:default;
}
.kpi::after {
    content:'';
    position:absolute; top:0; left:0; right:0;
    height:3px; background:var(--c, var(--brand));
    border-radius:var(--r-lg) var(--r-lg) 0 0;
}
.kpi:hover {
    transform:translateY(-3px);
    box-shadow:var(--sh-lg);
    border-color:transparent;
}
.kpi-row1 {
    display:flex; justify-content:space-between;
    align-items:flex-start; margin-bottom:.9rem;
}
.kpi-ic {
    width:44px; height:44px; border-radius:var(--r-md);
    display:flex; align-items:center; justify-content:center;
    font-size:1.15rem;
    background:var(--ib, var(--brand-lt));
    color:var(--c, var(--brand));
    flex-shrink:0;
}
.kpi-badge {
    font-size:.68rem; font-weight:700; padding:.18rem .48rem;
    border-radius:5px; letter-spacing:.2px;
}
.kpi-badge-up   { background:#e1f5ee; color:#0f6e56; }
.kpi-badge-neu  { background:var(--bg); color:var(--t3); }
.kpi-badge-warn { background:#faeeda; color:#854f0b; }
.kpi-lbl {
    font-size:.69rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.55px; color:var(--t3); margin-bottom:.25rem;
}
.kpi-val {
    font-size:2.05rem; font-weight:750; color:var(--t1);
    line-height:1; letter-spacing:-.5px;
}
.kpi-hint { font-size:.72rem; color:var(--t3); margin-top:3px; }

/* ── Panel ───────────────────────────────────────── */
.pnl {
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--r-lg);
    overflow:hidden;
    transition:box-shadow .2s;
}
.pnl:hover { box-shadow:var(--sh-md); }
.pnl-hd {
    display:flex; justify-content:space-between; align-items:center;
    padding:1rem 1.3rem; border-bottom:1px solid var(--border);
}
.pnl-title {
    font-size:.9rem; font-weight:700; color:var(--t1); margin:0;
    display:flex; align-items:center; gap:7px;
}
.pnl-title i { color:var(--brand); }
.pnl-badge {
    background:var(--brand-lt); color:var(--brand);
    font-size:.7rem; font-weight:700;
    padding:.2rem .55rem; border-radius:6px;
}

/* ── Grilles contenu ─────────────────────────────── */
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
.g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; }
.g-main { display:grid; grid-template-columns:1fr 360px; gap:1.25rem; }
@media(max-width:1100px){
    .g2,.g3,.g-main { grid-template-columns:1fr; }
}

/* ── Table moderne ───────────────────────────────── */
.adm-tbl { width:100%; border-collapse:collapse; }
.adm-tbl thead th {
    background:var(--bg); font-size:.68rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.6px; color:var(--t3);
    padding:.65rem 1rem; white-space:nowrap;
    border-bottom:1px solid var(--border);
}
.adm-tbl tbody tr {
    border-bottom:1px solid #f5f4fa;
    transition:background .12s;
}
.adm-tbl tbody tr:last-child { border:none; }
.adm-tbl tbody tr:hover { background:#faf9fd; }
.adm-tbl td { padding:.8rem 1rem; vertical-align:middle; }

/* ── Pills ───────────────────────────────────────── */
.pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:.2rem .58rem; border-radius:6px;
    font-size:.7rem; font-weight:700; letter-spacing:.2px;
}
.pill-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }
.p-green  { background:#e1f5ee; color:#0f6e56; }
.p-amber  { background:#faeeda; color:#854f0b; }
.p-purple { background:var(--brand-lt); color:var(--brand); }
.p-blue   { background:#e6f1fb; color:#185fa5; }
.p-red    { background:#fcebeb; color:#a32d2d; }

/* Avatar initials */
.av {
    width:34px; height:34px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.73rem; font-weight:700; flex-shrink:0;
}

/* ── Action buttons ──────────────────────────────── */
.ab {
    width:29px; height:29px; border-radius:var(--r-sm);
    border:1px solid var(--border); background:var(--surface);
    color:var(--t2); display:inline-flex;
    align-items:center; justify-content:center;
    font-size:.78rem; text-decoration:none; transition:var(--tr);
}
.ab:hover { border-color:var(--brand); color:var(--brand); background:var(--brand-lt); }
.ab.solid { background:var(--brand); border-color:var(--brand); color:#fff; }
.ab.solid:hover { background:var(--brand-dk); }

/* ── Activity feed ───────────────────────────────── */
.feed-item {
    display:flex; align-items:flex-start; gap:11px;
    padding:.8rem 1.1rem; border-bottom:1px solid #f5f4fa;
    transition:background .12s; position:relative;
}
.feed-item:last-child { border:none; }
.feed-item:hover { background:#faf9fd; }
.feed-ic {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.83rem; flex-shrink:0;
}
.feed-txt { font-size:.84rem; font-weight:600; color:var(--t1); line-height:1.3; }
.feed-sub {
    font-size:.75rem; color:var(--t3); margin-top:1px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:200px;
}
.feed-time { font-size:.7rem; color:var(--t3); margin-left:auto; white-space:nowrap; flex-shrink:0; }

/* ── Quick actions ───────────────────────────────── */
.qa-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; padding:1.1rem; }
.qa-btn {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:8px; padding:1.1rem .75rem; border-radius:var(--r-md);
    border:1.5px solid var(--border); background:var(--surface);
    text-decoration:none; color:var(--t1); transition:var(--tr);
    font-size:.8rem; font-weight:600; text-align:center;
}
.qa-btn:hover {
    border-color:var(--brand); background:var(--brand-lt);
    color:var(--brand); transform:translateY(-2px); box-shadow:var(--sh-sm);
}
.qa-btn i { font-size:1.4rem; color:var(--brand); }

/* ── Top courses ─────────────────────────────────── */
.tc-row {
    display:flex; align-items:center; gap:10px;
    padding:.8rem 1.1rem; border-bottom:1px solid #f5f4fa;
    transition:background .12s;
}
.tc-row:last-child { border:none; }
.tc-row:hover { background:#faf9fd; }
.tc-rank {
    width:24px; font-size:.78rem; font-weight:700;
    color:var(--t3); text-align:center; flex-shrink:0;
}
.tc-rank.top { color:var(--brand); }
.tc-name {
    font-size:.84rem; font-weight:600; color:var(--t1);
    flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.tc-meta { font-size:.73rem; color:var(--t3); }
.tc-val {
    font-size:.84rem; font-weight:700; color:var(--brand);
    flex-shrink:0;
}

/* ── Bar progress ────────────────────────────────── */
.bar { height:4px; background:#ebe9f1; border-radius:999px; overflow:hidden; }
.bar-fill { height:100%; border-radius:999px; background:var(--brand); transition:width 1.2s cubic-bezier(.4,0,.2,1); }

/* ── Animations ──────────────────────────────────── */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
.an { opacity:0; }
.an.in { animation:fadeUp .5s ease forwards; }
</style>

<div class="adm">

{{-- ════ HEADER ════════════════════════════════════ --}}
<div class="adm-hd an">
    <div>
        <div class="adm-eyebrow">
            <i class="bi bi-shield-check me-1"></i>Administration
        </div>
        <h1 class="adm-title">Dashboard Admin</h1>
        <p class="adm-sub">
            Vue d'ensemble de la plateforme —
            {{ now()->isoFormat('dddd D MMMM YYYY') }}
        </p>
    </div>
    <div class="hd-actions">
        <a href="{{ route('courses.create') }}" class="btn-hd btn-hd-primary">
            <i class="bi bi-plus-lg"></i>Nouveau cours
        </a>
        <a href="{{ route('courses.index') }}" class="btn-hd btn-hd-ghost">
            <i class="bi bi-collection-play"></i>Catalogue
        </a>
        <a href="{{ route('dashboard.formateur') }}" class="btn-hd btn-hd-ghost">
            <i class="bi bi-bar-chart-line"></i>Formateur
        </a>
    </div>
</div>

{{-- ════ KPI CARDS ═════════════════════════════════ --}}
<div class="kpi-grid">

    <div class="kpi an" style="--c:#6c63ff;--ib:#eeedfe;animation-delay:.05s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-people"></i></div>
            <span class="kpi-badge kpi-badge-up">
                <i class="bi bi-arrow-up-short"></i>total
            </span>
        </div>
        <div class="kpi-lbl">Utilisateurs</div>
        <div class="kpi-val" data-count="{{ $totalUsers }}">0</div>
        <div class="kpi-hint">
            {{ $totalFormateurs }} formateurs · {{ $totalEtudiants }} étudiants
        </div>
    </div>

    <div class="kpi an" style="--c:#0f6e56;--ib:#e1f5ee;animation-delay:.1s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-collection-play"></i></div>
            <span class="kpi-badge kpi-badge-up">actifs</span>
        </div>
        <div class="kpi-lbl">Cours</div>
        <div class="kpi-val" data-count="{{ $totalCourses }}">0</div>
        <div class="kpi-hint">{{ $publishedCourses }} publiés · {{ $totalLessons }} leçons</div>
    </div>

    <div class="kpi an" style="--c:#854f0b;--ib:#faeeda;animation-delay:.15s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-cash-stack"></i></div>
            <span class="kpi-badge kpi-badge-neu">estimé</span>
        </div>
        <div class="kpi-lbl">Paiements simulés estimés</div>
        <div class="kpi-val">
            {{ number_format($revenueGlobal, 0, ',', ' ') }} €
        </div>
        <div class="kpi-hint">Basé sur les inscriptions payées · aucun paiement réel</div>
    </div>

    <div class="kpi an" style="--c:#993c1d;--ib:#faece7;animation-delay:.2s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-patch-question"></i></div>
            <span class="kpi-badge kpi-badge-up">
                <i class="bi bi-check-circle"></i>quiz
            </span>
        </div>
        <div class="kpi-lbl">Quiz complétés</div>
        <div class="kpi-val" data-count="{{ $totalQuizzes }}">0</div>
        <div class="kpi-hint">{{ $totalReviews }} avis déposés</div>
    </div>

    <div class="kpi an" style="--c:#185fa5;--ib:#e6f1fb;animation-delay:.22s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-person-workspace"></i></div>
            <span class="kpi-badge kpi-badge-warn">formateurs</span>
        </div>
        <div class="kpi-lbl">Formateurs</div>
        <div class="kpi-val" data-count="{{ $totalFormateurs }}">0</div>
        <div class="kpi-hint">créateurs de contenu</div>
    </div>

    <div class="kpi an" style="--c:#198754;--ib:#e1f5ee;animation-delay:.25s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-mortarboard"></i></div>
            <span class="kpi-badge kpi-badge-up">inscrits</span>
        </div>
        <div class="kpi-lbl">Étudiants</div>
        <div class="kpi-val" data-count="{{ $totalEtudiants }}">0</div>
        <div class="kpi-hint">apprenants actifs</div>
    </div>

    <div class="kpi an" style="--c:#534ab7;--ib:#eeedfe;animation-delay:.28s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-journal-check"></i></div>
            <span class="kpi-badge kpi-badge-neu">leçons</span>
        </div>
        <div class="kpi-lbl">Leçons créées</div>
        <div class="kpi-val" data-count="{{ $totalLessons }}">0</div>
        <div class="kpi-hint">sur {{ $totalCourses }} cours</div>
    </div>

    <div class="kpi an" style="--c:#a32d2d;--ib:#fcebeb;animation-delay:.3s">
        <div class="kpi-row1">
            <div class="kpi-ic"><i class="bi bi-star-half"></i></div>
            <span class="kpi-badge kpi-badge-up">avis</span>
        </div>
        <div class="kpi-lbl">Avis déposés</div>
        <div class="kpi-val" data-count="{{ $totalReviews }}">0</div>
        <div class="kpi-hint">retours étudiants</div>
    </div>

</div>

{{-- ════ GRAPHIQUES ═════════════════════════════════ --}}
<div class="g3 an" style="animation-delay:.32s;margin-bottom:1.25rem">

    {{-- Inscriptions / mois --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title"><i class="bi bi-graph-up"></i>Inscriptions</h2>
            <span class="pnl-badge">6 mois</span>
        </div>
        <div style="padding:1rem">
            <canvas id="ch-enroll" height="180"></canvas>
        </div>
    </div>

    {{-- Nouveaux utilisateurs --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title"><i class="bi bi-person-plus"></i>Nouveaux users</h2>
            <span class="pnl-badge">6 mois</span>
        </div>
        <div style="padding:1rem">
            <canvas id="ch-users" height="180"></canvas>
        </div>
    </div>

    {{-- Répartition rôles --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title"><i class="bi bi-pie-chart"></i>Répartition</h2>
            <span class="pnl-badge">rôles</span>
        </div>
        <div style="padding:1rem;display:flex;align-items:center;justify-content:center">
            <canvas id="ch-roles" height="180" style="max-width:180px"></canvas>
        </div>
        <div style="padding:0 1rem .75rem;display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center">
            @foreach(['#534ab7','#0f6e56','#185fa5'] as $i => $col)
            @php $lbl = $rolesData['labels'][$i]; $val = $rolesData['data'][$i]; @endphp
            <span style="display:inline-flex;align-items:center;gap:5px;
                         font-size:.74rem;color:var(--t2)">
                <span style="width:10px;height:10px;border-radius:50%;
                             background:{{ $col }};display:inline-block"></span>
                {{ $lbl }} ({{ $val }})
            </span>
            @endforeach
        </div>
    </div>

</div>

{{-- ════ TABLE + SIDEBAR ════════════════════════════ --}}
<div class="g-main an" style="animation-delay:.36s;margin-bottom:1.25rem">

    {{-- Derniers utilisateurs --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title"><i class="bi bi-people"></i>Derniers utilisateurs</h2>
            <span class="pnl-badge">{{ $latestUsers->count() }}</span>
        </div>
        <div style="overflow-x:auto">
            <table class="adm-tbl">
                <thead>
                    <tr>
                        <th style="padding-left:1.2rem">Utilisateur</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                        <th style="padding-right:1.1rem"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestUsers as $u)
                    @php
                        $roleConf = match($u->role) {
                            'admin'     => ['cls'=>'p-red',    'label'=>'Admin'],
                            'formateur' => ['cls'=>'p-purple', 'label'=>'Formateur'],
                            default     => ['cls'=>'p-blue',   'label'=>'Étudiant'],
                        };
                        $avBg = match($u->role) {
                            'admin'     => '#fcebeb', 'formateur' => '#eeedfe',
                            default     => '#e6f1fb',
                        };
                        $avCol = match($u->role) {
                            'admin'     => '#a32d2d', 'formateur' => '#534ab7',
                            default     => '#185fa5',
                        };
                    @endphp
                    <tr>
                        <td style="padding-left:1.1rem">
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="av"
                                     style="background:{{ $avBg }};color:{{ $avCol }}">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.86rem;color:var(--t1)">
                                        {{ $u->name }}
                                    </div>
                                    <div style="font-size:.73rem;color:var(--t3)">
                                        {{ $u->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="pill {{ $roleConf['cls'] }}">
                                <span class="pill-dot"></span>
                                {{ $roleConf['label'] }}
                            </span>
                        </td>
                        <td style="font-size:.78rem;color:var(--t3)">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                        <td style="padding-right:1.1rem;text-align:right">
                            <a href="{{ route('courses.index') }}"
                               class="ab" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sidebar droite --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Actions rapides --}}
        <div class="pnl">
            <div class="pnl-hd">
                <h2 class="pnl-title">
                    <i class="bi bi-lightning-charge"></i>Actions rapides
                </h2>
            </div>
            <div class="qa-grid">
                <a href="{{ route('courses.create') }}" class="qa-btn">
                    <i class="bi bi-plus-circle"></i>Créer cours
                </a>
                <a href="{{ route('courses.index') }}" class="qa-btn">
                    <i class="bi bi-collection-play"></i>Catalogue
                </a>
                <a href="{{ route('dashboard.formateur') }}" class="qa-btn">
                    <i class="bi bi-bar-chart-line"></i>Stats
                </a>
                <a href="{{ route('dashboard.student') }}" class="qa-btn">
                    <i class="bi bi-mortarboard"></i>Étudiants
                </a>
            </div>
        </div>

        {{-- Activité récente --}}
        <div class="pnl" style="flex:1">
            <div class="pnl-hd">
                <h2 class="pnl-title">
                    <i class="bi bi-clock-history"></i>Activité
                </h2>
                <span class="pnl-badge">live</span>
            </div>
            @if($activity->isNotEmpty())
            @foreach($activity as $act)
            <div class="feed-item">
                <div class="feed-ic"
                     style="background:{{ $act['bg'] }};color:{{ $act['color'] }}">
                    <i class="bi bi-{{ $act['icon'] }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="feed-txt">{{ $act['title'] }}</div>
                    <div class="feed-sub">{{ $act['sub'] }}</div>
                </div>
                <div class="feed-time">{{ $act['time'] }}</div>
            </div>
            @endforeach
            @else
            <div style="text-align:center;padding:2rem;color:var(--t3);font-size:.85rem">
                <i class="bi bi-clock" style="font-size:1.8rem;display:block;margin-bottom:.5rem;color:#d3d1c7"></i>
                Aucune activité récente.
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ════ DERNIERS COURS + TOP COURS ════════════════ --}}
<div class="g2 an" style="animation-delay:.4s">

    {{-- Derniers cours --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title">
                <i class="bi bi-collection-play"></i>Derniers cours
            </h2>
            <span class="pnl-badge">{{ $latestCourses->count() }}</span>
        </div>
        <div style="overflow-x:auto">
            <table class="adm-tbl">
                <thead>
                    <tr>
                        <th style="padding-left:1.1rem">Cours</th>
                        <th>Formateur</th>
                        <th class="text-center">Inscrits</th>
                        <th>Statut</th>
                        <th style="padding-right:1.1rem"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestCourses as $c)
                    <tr>
                        <td style="padding-left:1.1rem;max-width:170px">
                            <div style="font-weight:600;font-size:.86rem;color:var(--t1);
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $c->title }}
                            </div>
                            <div style="font-size:.72rem;color:var(--t3)">
                                {{ $c->category?->name ?? '—' }}
                            </div>
                        </td>
                        <td style="font-size:.82rem;color:var(--t2);white-space:nowrap">
                            {{ $c->formateur?->name ?? '—' }}
                        </td>
                        <td class="text-center">
                            <span style="font-weight:700;font-size:.88rem;color:var(--t1)">
                                {{ $c->students_count }}
                            </span>
                        </td>
                        <td>
                            @if($c->is_published)
                                <span class="pill p-green">
                                    <span class="pill-dot"></span>Publié
                                </span>
                            @else
                                <span class="pill p-amber">
                                    <span class="pill-dot"></span>Brouillon
                                </span>
                            @endif
                        </td>
                        <td style="padding-right:1.1rem;text-align:right">
                            <a href="{{ route('courses.show', $c) }}" class="ab">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top cours par inscrits --}}
    <div class="pnl">
        <div class="pnl-hd">
            <h2 class="pnl-title">
                <i class="bi bi-trophy"></i>Top cours
            </h2>
            <span class="pnl-badge">par inscrits</span>
        </div>
        @php $maxS = $topCourses->max('students_count') ?: 1; @endphp
        @foreach($topCourses as $idx => $tc)
        <div class="tc-row">
            <div class="tc-rank {{ $idx < 3 ? 'top' : '' }}">
                {{ $idx < 3 ? ['🥇','🥈','🥉'][$idx] : $idx+1 }}
            </div>
            <div style="flex:1;min-width:0">
                <div class="tc-name">{{ $tc->title }}</div>
                <div style="display:flex;align-items:center;gap:6px;margin-top:4px">
                    <div class="bar" style="flex:1">
                        <div class="bar-fill"
                             style="width:{{ round($tc->students_count/$maxS*100) }}%">
                        </div>
                    </div>
                    @if($tc->reviews_avg_rating)
                    <span style="font-size:.7rem;color:#f39c12;flex-shrink:0">
                        ★ {{ number_format($tc->reviews_avg_rating, 1) }}
                    </span>
                    @endif
                </div>
                <div class="tc-meta" style="margin-top:1px">
                    {{ $tc->formateur?->name ?? '—' }}
                </div>
            </div>
            <div class="tc-val">{{ $tc->students_count }}</div>
        </div>
        @endforeach
        @if($topCourses->isEmpty())
        <div style="text-align:center;padding:2rem;color:var(--t3);font-size:.85rem">
            Aucun cours disponible.
        </div>
        @endif
    </div>

</div>

</div>{{-- /adm --}}

{{-- ════ SCRIPTS ════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Intersection Observer → fade-in ─────────── */
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e, i) => {
            if (!e.isIntersecting) return;
            setTimeout(() => e.target.classList.add('in'), i * 35);
            io.unobserve(e.target);
        });
    }, { threshold: 0.07 });
    document.querySelectorAll('.an').forEach(el => io.observe(el));

    /* ── Count-up ─────────────────────────────────── */
    const co = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target, target = +el.dataset.count;
            if (isNaN(target)) return;
            const dur = 1100, t0 = performance.now();
            const run = now => {
                const p = Math.min((now - t0) / dur, 1);
                el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target)
                                      .toLocaleString('fr');
                if (p < 1) requestAnimationFrame(run);
            };
            requestAnimationFrame(run);
            co.unobserve(el);
        });
    }, { threshold: 0.3 });
    document.querySelectorAll('[data-count]').forEach(el => co.observe(el));

    /* ── Chart defaults ───────────────────────────── */
    const base = {
        responsive: true,
        animation: { duration: 800, easing: 'easeOutQuart' },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1a2e', titleColor: '#fff',
                bodyColor: '#c0bfe0', padding: 10, cornerRadius: 8,
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

    const labels = {!! $monthLabels->toJson() !!};

    /* ── Inscriptions line ────────────────────────── */
    const ce = document.getElementById('ch-enroll');
    if (ce) new Chart(ce, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: {!! $monthValues->toJson() !!},
                borderColor: '#6c63ff',
                backgroundColor: 'rgba(108,99,255,.08)',
                tension: .4, fill: true, pointRadius: 4,
                pointBackgroundColor: '#6c63ff',
                pointBorderColor: '#fff', pointBorderWidth: 2,
            }]
        },
        options: {
            ...base,
            plugins: {
                ...base.plugins,
                tooltip: {
                    ...base.plugins.tooltip,
                    callbacks: { label: c => ` ${c.parsed.y} inscription(s)` }
                }
            }
        }
    });

    /* ── Nouveaux users bar ───────────────────────── */
    const cu = document.getElementById('ch-users');
    if (cu) new Chart(cu, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: {!! $usersMonthValues->toJson() !!},
                backgroundColor: 'rgba(15,110,86,.1)',
                borderColor: '#0f6e56',
                borderWidth: 2, borderRadius: 7, borderSkipped: false,
                hoverBackgroundColor: 'rgba(15,110,86,.2)',
            }]
        },
        options: {
            ...base,
            plugins: {
                ...base.plugins,
                tooltip: {
                    ...base.plugins.tooltip,
                    callbacks: { label: c => ` ${c.parsed.y} utilisateur(s)` }
                }
            }
        }
    });

    /* ── Donut rôles ──────────────────────────────── */
    const cr = document.getElementById('ch-roles');
    if (cr) new Chart(cr, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($rolesData['labels']) !!},
            datasets: [{
                data: {!! json_encode($rolesData['data']) !!},
                backgroundColor: ['#534ab7','#0f6e56','#185fa5'],
                borderColor: '#fff', borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 800 },
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a2e', titleColor: '#fff',
                    bodyColor: '#c0bfe0', padding: 10, cornerRadius: 8,
                }
            }
        }
    });

    /* ── Bar perf animation ───────────────────────── */
    document.querySelectorAll('.bar-fill').forEach((b, i) => {
        const w = b.style.width;
        b.style.width = '0%';
        setTimeout(() => { b.style.width = w; }, 500 + i * 100);
    });

});
</script>

@endsection
