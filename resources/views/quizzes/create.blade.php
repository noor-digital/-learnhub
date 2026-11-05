@extends('layouts.app')
@section('title', $hasActiveQuiz ? 'Nouvelle version du quiz' : 'Créer un quiz')
@section('content')
<style>
.qb { max-width: 760px; margin: 0 auto; padding: 2rem 1rem; }
.q-blk {
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem;
}
.q-blk:focus-within { box-shadow: 0 4px 20px rgba(108,99,255,.09); }
.q-hd { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.a-row { display: flex; align-items: center; gap: 7px; margin-bottom: .45rem; }
.btn-ghost {
    font-size: .79rem; padding: .3rem .85rem; border-radius: 7px;
    border: 1px dashed #d3d1c7; background: #f8f7f4; color: #666;
    cursor: pointer; transition: all .15s;
}
.btn-ghost:hover { border-color: var(--brand); color: var(--brand); background: #eeedfe; }
.btn-del {
    width: 28px; height: 28px; border-radius: 6px;
    background: #fcebeb; border: none; color: #a32d2d;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; font-size: .8rem; flex-shrink: 0;
}
.correct-lbl {
    display: flex; align-items: center; gap: 4px;
    font-size: .74rem; color: #0f6e56;
    cursor: pointer; flex-shrink: 0; white-space: nowrap;
}
.correct-lbl input { cursor: pointer; accent-color: #0f6e56; }
</style>

<div class="qb">
    <div class="mb-4">
        <a href="{{ route('courses.show', $course) }}"
           style="color:var(--brand);text-decoration:none;font-size:.82rem">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <h1 class="h4 fw-bold mt-2 mb-0">{{ $hasActiveQuiz ? 'Créer une nouvelle version du quiz' : 'Créer un quiz' }}</h1>
        <p class="text-muted small">{{ $course->title }}</p>
        @if($hasActiveQuiz)
            <div class="alert alert-info mt-3 mb-0" role="status" style="border-radius:10px">
                Vous créez une nouvelle version. L’ancienne version, ses questions et les résultats déjà obtenus seront conservés.
            </div>
        @endif
    </div>

    @if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:9px;font-size:.88rem">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('quiz.store', $course) }}">
        @csrf

        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">Paramètres du quiz</h2>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Titre *</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title') }}"
                           placeholder="Ex : Quiz final — JavaScript"
                           style="border-radius:8px" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Description</label>
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Instructions..."
                              style="border-radius:8px">{{ old('description') }}</textarea>
                </div>

                <div style="max-width:180px">
                    <label class="form-label fw-semibold small">Score minimum (%) *</label>
                    <input type="number" name="pass_score" class="form-control"
                           value="{{ old('pass_score', 70) }}"
                           min="1" max="100" style="border-radius:8px">
                    <div class="form-text">Seuil de réussite.</div>
                </div>
            </div>
        </div>

        <h2 class="h6 fw-bold mb-3">Questions</h2>
        <div id="qwrap"></div>

        <button type="button" class="btn-ghost w-100 py-2 mb-4" onclick="addQ()">
            <i class="bi bi-plus-circle me-1"></i>Ajouter une question
        </button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn fw-semibold px-4"
                    style="background:var(--brand);color:#fff;border-radius:8px;border:none">
                <i class="bi bi-check-lg me-1"></i>{{ $hasActiveQuiz ? 'Enregistrer la nouvelle version' : 'Créer le quiz' }}
            </button>
            <a href="{{ route('courses.show', $course) }}"
               class="btn btn-outline-secondary" style="border-radius:8px">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
let qc = 0;
const ac = {};

function addQ() {
    qc++;
    const qi = qc;
    ac[qi] = 0;
    const wrap = document.getElementById('qwrap');
    const d = document.createElement('div');
    d.className = 'q-blk';
    d.id = 'qb' + qi;
    d.innerHTML = `
        <div class="q-hd">
            <span class="fw-semibold" style="font-size:.9rem;color:#1a1a2e">
                <i class="bi bi-question-circle me-1" style="color:var(--brand)"></i>
                Question ${qi}
            </span>
            <button type="button" class="btn-del"
                    onclick="document.getElementById('qb${qi}').remove()">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="mb-3">
            <input type="text" name="questions[${qi}][text]"
                   class="form-control form-control-sm"
                   placeholder="Texte de la question *"
                   style="border-radius:8px" required>
        </div>
        <div class="mb-2 text-muted" style="font-size:.75rem">
            <i class="bi bi-info-circle me-1"></i>
            Cochez "Bonne réponse" pour marquer la réponse correcte.
        </div>
        <div id="aw${qi}"></div>
        <button type="button" class="btn-ghost mt-1" onclick="addA(${qi})">
            <i class="bi bi-plus me-1"></i>Ajouter une réponse
        </button>`;
    wrap.appendChild(d);
    addA(qi); addA(qi); addA(qi);
}

function addA(qi) {
    ac[qi] = (ac[qi] || 0) + 1;
    const ai = ac[qi];
    const wrap = document.getElementById('aw' + qi);
    const row = document.createElement('div');
    row.className = 'a-row';
    row.id = `ar${qi}_${ai}`;
    row.innerHTML = `
        <input type="text"
               name="questions[${qi}][answers][${ai}][text]"
               class="form-control form-control-sm"
               placeholder="Réponse..."
               style="border-radius:7px;flex:1" required>
        <label class="correct-lbl" title="Bonne réponse">
            <input type="radio"
                   name="cq${qi}"
                   id="cr${qi}_${ai}"
                   onchange="setC(${qi},${ai})">
            Bonne réponse
        </label>
        <input type="hidden"
               name="questions[${qi}][answers][${ai}][correct]"
               id="hid${qi}_${ai}" value="0">
        <button type="button" class="btn-del"
                onclick="document.getElementById('ar${qi}_${ai}').remove()">
            <i class="bi bi-x"></i>
        </button>`;
    wrap.appendChild(row);
}

function setC(qi, ai) {
    document.querySelectorAll(`[id^="hid${qi}_"]`).forEach(h => h.value = '0');
    const h = document.getElementById(`hid${qi}_${ai}`);
    if (h) h.value = '1';
}

addQ();
</script>
@endsection