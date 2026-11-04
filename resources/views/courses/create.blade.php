@extends('layouts.app')
@section('title', 'Créer un cours')
@section('content')
<style>
.wizard-wrap {
    max-width: 820px; margin: 0 auto; padding: 2rem 1rem;
}

/* ── Steps bar ─────────────────────────────────────── */
.steps-bar {
    display: flex; align-items: center;
    margin-bottom: 2.5rem; position: relative;
}
.steps-bar::before {
    content: '';
    position: absolute; top: 20px; left: 0; right: 0;
    height: 2px; background: #e8e6e0; z-index: 0;
}
.step-item {
    display: flex; flex-direction: column;
    align-items: center; flex: 1; position: relative; z-index: 1;
}
.step-dot {
    width: 40px; height: 40px; border-radius: 50%;
    border: 2px solid #e8e6e0; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; color: #bbb;
    transition: all .3s; cursor: pointer; margin-bottom: 6px;
}
.step-item.active .step-dot {
    background: var(--brand); border-color: var(--brand);
    color: #fff; box-shadow: 0 0 0 4px rgba(108,99,255,.18);
}
.step-item.done .step-dot {
    background: #198754; border-color: #198754; color: #fff;
}
.step-label {
    font-size: .72rem; color: #aaa; font-weight: 500;
    white-space: nowrap;
}
.step-item.active .step-label { color: var(--brand); font-weight: 600; }
.step-item.done .step-label   { color: #198754; }

/* ── Step panels ───────────────────────────────────── */
.step-panel { display: none; animation: fadeUp .35s ease both; }
.step-panel.active { display: block; }
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Section cards ─────────────────────────────────── */
.w-card {
    background: #fff; border: 1px solid #e8e6e0;
    border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
}
.w-card-hd {
    padding: 1.1rem 1.4rem .9rem;
    border-bottom: 1px solid #f0efe9;
    display: flex; align-items: center; gap: 10px;
}
.w-card-icon {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
}
.w-card-title {
    font-size: .94rem; font-weight: 600; color: #1a1a2e; margin: 0;
}
.w-card-desc {
    font-size: .76rem; color: #aaa; margin: 1px 0 0;
}
.w-card-body { padding: 1.25rem 1.4rem; }

/* ── Inputs ────────────────────────────────────────── */
.lbl {
    font-size: .8rem; font-weight: 600; color: #444;
    margin-bottom: 5px; display: block;
}
.lbl span { color: #dc3545; margin-left: 2px; }
.hint {
    font-size: .73rem; color: #aaa; margin-top: 4px;
}
.form-control, .form-select {
    border-radius: 9px !important;
    border: 1.5px solid #e8e6e0 !important;
    font-size: .88rem !important;
    transition: border-color .15s, box-shadow .15s !important;
}
.form-control:focus, .form-select:focus {
    border-color: var(--brand) !important;
    box-shadow: 0 0 0 3px rgba(108,99,255,.1) !important;
    outline: none !important;
}

/* ── Thumbnail preview ─────────────────────────────── */
.thumb-zone {
    border: 2px dashed #d3d1c7; border-radius: 12px;
    padding: 2rem; text-align: center; cursor: pointer;
    transition: all .2s; background: #f8f7f4;
}
.thumb-zone:hover { border-color: var(--brand); background: #f3f2ff; }
.thumb-zone.has-img { padding: 0; border-style: solid; border-color: #d3d1c7; overflow: hidden; }
.thumb-preview {
    width: 100%; max-height: 260px;
    object-fit: cover; border-radius: 10px; display: none;
}

/* ── Price toggle ──────────────────────────────────── */
.price-toggle {
    display: flex; gap: 8px; margin-bottom: 1rem;
}
.pt-btn {
    flex: 1; padding: .65rem; border-radius: 9px;
    border: 1.5px solid #e8e6e0; background: #fff;
    cursor: pointer; text-align: center; transition: all .15s;
    font-size: .85rem; font-weight: 500; color: #666;
}
.pt-btn.on {
    border-color: var(--brand); background: #eeedfe;
    color: var(--brand); font-weight: 600;
}

/* ── Nav buttons ───────────────────────────────────── */
.wizard-nav {
    display: flex; justify-content: space-between;
    align-items: center; margin-top: 1.5rem;
}
.btn-prev {
    display: inline-flex; align-items: center; gap: 6px;
    padding: .6rem 1.2rem; border-radius: 9px;
    border: 1.5px solid #e8e6e0; background: #fff;
    color: #555; font-size: .88rem; font-weight: 500;
    cursor: pointer; transition: all .15s;
}
.btn-prev:hover { border-color: #ccc; background: #f8f7f4; }
.btn-next {
    display: inline-flex; align-items: center; gap: 6px;
    padding: .6rem 1.4rem; border-radius: 9px;
    background: var(--brand); border: none;
    color: #fff; font-size: .88rem; font-weight: 600;
    cursor: pointer; transition: all .15s;
}
.btn-next:hover { background: #5a52d5; transform: translateY(-1px); }

/* ── Publish toggle ────────────────────────────────── */
.pub-toggle {
    display: flex; align-items: center; gap: 12px;
    padding: 1rem 1.2rem; border-radius: 10px;
    border: 1.5px solid #e8e6e0; cursor: pointer;
    transition: all .15s;
}
.pub-toggle:hover { border-color: var(--brand); background: #f8f7f8; }
.pub-toggle input { display: none; }
.pub-toggle-dot {
    width: 42px; height: 24px; border-radius: 999px;
    background: #e8e6e0; position: relative;
    transition: background .2s; flex-shrink: 0;
}
.pub-toggle-dot::after {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%;
    background: #fff; top: 3px; left: 3px;
    transition: transform .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.pub-toggle.on .pub-toggle-dot { background: #198754; }
.pub-toggle.on .pub-toggle-dot::after { transform: translateX(18px); }
</style>

<div class="wizard-wrap">

    <div class="mb-4">
        <a href="{{ route('courses.index') }}"
           style="color:var(--brand);text-decoration:none;font-size:.82rem">
            <i class="bi bi-arrow-left me-1"></i>Retour au catalogue
        </a>
        <h1 class="h4 fw-bold mt-2 mb-0" style="color:#1a1a2e">
            Créer un nouveau cours
        </h1>
        <p class="text-muted small">
            Suivez les étapes pour configurer votre cours.
        </p>
    </div>

    {{-- Steps bar --}}
    <div class="steps-bar">
        @foreach(['Informations', 'Description', 'Média', 'Paramètres'] as $i => $label)
        <div class="step-item {{ $i === 0 ? 'active' : '' }}"
             data-step="{{ $i }}">
            <div class="step-dot">
                <i class="bi bi-check-lg" style="display:none"></i>
                <span class="step-num">{{ $i + 1 }}</span>
            </div>
            <span class="step-label">{{ $label }}</span>
        </div>
        @endforeach
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
    <div class="alert alert-danger mb-4"
         style="border-radius:10px;font-size:.87rem;border-left:3px solid #dc3545">
        <i class="bi bi-exclamation-circle me-2"></i>
        <strong>Corrige les erreurs suivantes :</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ route('courses.store') }}"
          enctype="multipart/form-data"
          id="course-form">
        @csrf

        {{-- ══ ÉTAPE 1 — Informations ══════════════════ --}}
        <div class="step-panel active" id="panel-0">

            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#eeedfe;color:var(--brand)">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Informations de base</div>
                        <div class="w-card-desc">
                            Titre, catégorie et formateur
                        </div>
                    </div>
                </div>
                <div class="w-card-body">

                    <div class="mb-3">
                        <label class="lbl" for="title">
                            Titre du cours<span>*</span>
                        </label>
                        <input type="text" id="title" name="title"
                               class="form-control"
                               placeholder="Ex : Maîtriser Laravel de zéro à expert"
                               value="{{ old('title') }}"
                               oninput="updatePreviewTitle(this.value)"
                               required>
                        <div class="hint">
                            <i class="bi bi-lightbulb me-1"></i>
                            Un bon titre est précis et contient le niveau
                            (débutant, avancé...).
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="lbl" for="category_id">
                            Catégorie<span>*</span>
                        </label>
                        <select id="category_id" name="category_id"
                                class="form-select" required>
                            <option value="">— Choisir une catégorie —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Aperçu titre en temps réel --}}
                    <div id="title-preview"
                         style="padding:.75rem 1rem;border-radius:9px;
                                background:#f8f7f4;border:1px solid #e8e6e0;
                                font-size:.88rem;color:#888;display:none">
                        <span style="color:#aaa;font-size:.74rem">Aperçu titre :</span><br>
                        <strong id="preview-title-text" style="color:#1a1a2e"></strong>
                    </div>

                </div>
            </div>

            <div class="wizard-nav">
                <div></div>
                <button type="button" class="btn-next" onclick="goStep(1)">
                    Étape suivante <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ ÉTAPE 2 — Description ═══════════════════ --}}
        <div class="step-panel" id="panel-1">

            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#e1f5ee;color:#0f6e56">
                        <i class="bi bi-text-paragraph"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Description du cours</div>
                        <div class="w-card-desc">
                            Décris le contenu, les objectifs et le public cible
                        </div>
                    </div>
                </div>
                <div class="w-card-body">
                    <label class="lbl" for="description">Description</label>
                    <textarea id="description" name="description"
                              class="form-control" rows="8"
                              placeholder="Ce cours vous permettra de...

Vous apprendrez :
- Point 1
- Point 2
- Point 3

Public cible : développeurs débutants..."
                              style="font-size:.88rem;line-height:1.7">{{ old('description') }}</textarea>
                    <div class="hint">
                        <i class="bi bi-info-circle me-1"></i>
                        Minimum 150 caractères recommandé pour une bonne visibilité.
                        <span id="char-count" style="color:var(--brand)"></span>
                    </div>
                </div>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn-prev" onclick="goStep(0)">
                    <i class="bi bi-arrow-left"></i> Précédent
                </button>
                <button type="button" class="btn-next" onclick="goStep(2)">
                    Étape suivante <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ ÉTAPE 3 — Média ══════════════════════════ --}}
        <div class="step-panel" id="panel-2">

            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#faeeda;color:#854f0b">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Image de couverture</div>
                        <div class="w-card-desc">
                            JPG, PNG ou WEBP — max 2 Mo — ratio 16/9 recommandé
                        </div>
                    </div>
                </div>
                <div class="w-card-body">

                    <div class="thumb-zone" id="thumb-zone"
                         onclick="document.getElementById('thumbnail').click()">
                        <img id="thumb-preview" class="thumb-preview" alt="Aperçu">
                        <div id="thumb-placeholder">
                            <i class="bi bi-cloud-upload"
                               style="font-size:2.2rem;color:#d3d1c7;display:block;
                                      margin-bottom:.5rem"></i>
                            <div style="font-size:.88rem;color:#888">
                                <strong>Clique pour choisir une image</strong><br>
                                <span style="font-size:.78rem;color:#aaa">
                                    ou glisse-dépose ici
                                </span>
                            </div>
                        </div>
                    </div>
                    <input type="file" id="thumbnail" name="thumbnail"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="d-none"
                           onchange="previewThumb(this)">

                    @error('thumbnail')
                    <div class="text-danger mt-2" style="font-size:.82rem">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn-prev" onclick="goStep(1)">
                    <i class="bi bi-arrow-left"></i> Précédent
                </button>
                <button type="button" class="btn-next" onclick="goStep(3)">
                    Étape suivante <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ ÉTAPE 4 — Paramètres ════════════════════ --}}
        <div class="step-panel" id="panel-3">

            {{-- Prix --}}
            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#faece7;color:#993c1d">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Tarification</div>
                        <div class="w-card-desc">
                            Gratuit ou payant
                        </div>
                    </div>
                </div>
                <div class="w-card-body">
                    <div class="price-toggle mb-3">
                        <div class="pt-btn on" id="pt-free" onclick="setPricing('free')">
                            <i class="bi bi-gift me-1"></i>Gratuit
                        </div>
                        <div class="pt-btn" id="pt-paid" onclick="setPricing('paid')">
                            <i class="bi bi-credit-card me-1"></i>Payant
                        </div>
                    </div>

                    <div id="price-field" style="display:none">
                        <label class="lbl" for="price">Prix (€)<span>*</span></label>
                        <div class="input-group">
                            <input type="number" id="price" name="price"
                                   class="form-control"
                                   value="{{ old('price', 0) }}"
                                   min="0" step="0.01"
                                   placeholder="29.99"
                                   style="border-radius:9px 0 0 9px!important">
                            <span class="input-group-text"
                                  style="border-radius:0 9px 9px 0;
                                         background:#f8f7f4;
                                         border:1.5px solid #e8e6e0;
                                         border-left:none">€</span>
                        </div>
                        <div class="hint">0 = cours gratuit.</div>
                    </div>

                    {{-- Champ caché pour prix = 0 si gratuit --}}
                    <input type="hidden" id="price-hidden" name="price" value="0">
                </div>
            </div>

            {{-- Publication --}}
            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#e6f1fb;color:#185fa5">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Visibilité</div>
                        <div class="w-card-desc">
                            Publier immédiatement ou garder en brouillon
                        </div>
                    </div>
                </div>
                <div class="w-card-body">
                    <label class="pub-toggle" id="pub-toggle" onclick="togglePub()">
                        <input type="checkbox" name="is_published"
                               id="is_published" value="1"
                               {{ old('is_published') ? 'checked' : '' }}>
                        <div class="pub-toggle-dot" id="pub-dot"></div>
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem;color:#1a1a2e">
                                Publier le cours
                            </div>
                            <div style="font-size:.76rem;color:#aaa" id="pub-status">
                                Le cours sera enregistré en brouillon.
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Récapitulatif --}}
            <div class="w-card" id="recap-card">
                <div class="w-card-hd">
                    <div class="w-card-icon"
                         style="background:#eeedfe;color:var(--brand)">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div>
                        <div class="w-card-title">Récapitulatif</div>
                        <div class="w-card-desc">Vérifiez avant de créer</div>
                    </div>
                </div>
                <div class="w-card-body">
                    <div style="font-size:.85rem;line-height:2">
                        <div>
                            <i class="bi bi-type me-2"
                               style="color:var(--brand)"></i>
                            <strong>Titre :</strong>
                            <span id="recap-title" class="text-muted">—</span>
                        </div>
                        <div>
                            <i class="bi bi-folder2 me-2"
                               style="color:var(--brand)"></i>
                            <strong>Catégorie :</strong>
                            <span id="recap-cat" class="text-muted">—</span>
                        </div>
                        <div>
                            <i class="bi bi-image me-2"
                               style="color:var(--brand)"></i>
                            <strong>Image :</strong>
                            <span id="recap-img" class="text-muted">Non définie</span>
                        </div>
                        <div>
                            <i class="bi bi-cash me-2"
                               style="color:var(--brand)"></i>
                            <strong>Prix :</strong>
                            <span id="recap-price" class="text-muted">Gratuit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn-prev" onclick="goStep(2)">
                    <i class="bi bi-arrow-left"></i> Précédent
                </button>
                <button type="submit" class="btn-next"
                        style="background:#198754;padding:.65rem 1.75rem">
                    <i class="bi bi-check-circle me-1"></i>Créer le cours
                </button>
            </div>
        </div>

    </form>
</div>

<script>
let currentStep = 0;
const STEPS = 4;
let isPaid = false;
let isPub  = document.getElementById('is_published').checked;

// ── Navigation étapes ───────────────────────────────
function goStep(to) {
    // Validation basique étape 1
    if (to > 0 && currentStep === 0) {
        const title = document.getElementById('title').value.trim();
        const cat   = document.getElementById('category_id').value;
        if (!title) { alert('Le titre est obligatoire.'); return; }
        if (!cat)   { alert('Choisissez une catégorie.'); return; }
    }

    // Quitter l'étape actuelle
    document.getElementById('panel-' + currentStep).classList.remove('active');
    document.querySelector('[data-step="' + currentStep + '"]').classList.remove('active');
    document.querySelector('[data-step="' + currentStep + '"]').classList.add('done');

    // Marquer visuellement
    const prevDots = document.querySelectorAll('.step-item');
    prevDots.forEach((el, i) => {
        el.classList.remove('active', 'done');
        if (i < to)  el.classList.add('done');
        if (i === to) el.classList.add('active');
    });

    currentStep = to;
    document.getElementById('panel-' + to).classList.add('active');

    // Mise à jour récapitulatif à l'étape 4
    if (to === 3) updateRecap();

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Preview titre ───────────────────────────────────
function updatePreviewTitle(val) {
    const preview = document.getElementById('title-preview');
    const text    = document.getElementById('preview-title-text');
    if (val.length > 2) {
        preview.style.display = 'block';
        text.textContent = val;
    } else {
        preview.style.display = 'none';
    }
}

// ── Compteur caractères ─────────────────────────────
document.getElementById('description').addEventListener('input', function () {
    const len = this.value.length;
    const el  = document.getElementById('char-count');
    el.textContent = len + ' caractères' + (len < 150 ? ' (minimum 150 recommandé)' : ' ✓');
    el.style.color = len >= 150 ? '#198754' : '#f39c12';
});

// ── Preview thumbnail ───────────────────────────────
function previewThumb(input) {
    if (!input.files || !input.files[0]) return;
    const reader  = new FileReader();
    const zone    = document.getElementById('thumb-zone');
    const preview = document.getElementById('thumb-preview');
    const holder  = document.getElementById('thumb-placeholder');
    reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        holder.style.display = 'none';
        zone.classList.add('has-img');
        document.getElementById('recap-img').textContent = input.files[0].name;
    };
    reader.readAsDataURL(input.files[0]);
}

// Drag & drop
const zone = document.getElementById('thumb-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--brand)'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('thumbnail').files = dt.files;
        previewThumb(document.getElementById('thumbnail'));
    }
});

// ── Tarification ────────────────────────────────────
function setPricing(type) {
    isPaid = type === 'paid';
    document.getElementById('pt-free').classList.toggle('on', !isPaid);
    document.getElementById('pt-paid').classList.toggle('on', isPaid);
    document.getElementById('price-field').style.display  = isPaid ? 'block' : 'none';
    document.getElementById('price-hidden').disabled = isPaid;
    document.getElementById('price').disabled         = !isPaid;
    updateRecap();
}

// ── Toggle publication ──────────────────────────────
function togglePub() {
    isPub = !isPub;
    const toggle = document.getElementById('pub-toggle');
    const chk    = document.getElementById('is_published');
    const status = document.getElementById('pub-status');
    toggle.classList.toggle('on', isPub);
    chk.checked = isPub;
    status.textContent = isPub
        ? 'Le cours sera visible dans le catalogue immédiatement.'
        : 'Le cours sera enregistré en brouillon.';
}
// Initialise si déjà coché (vieux formulaire)
if (isPub) document.getElementById('pub-toggle').classList.add('on');

// ── Récapitulatif ───────────────────────────────────
function updateRecap() {
    const title  = document.getElementById('title').value || '—';
    const catEl  = document.getElementById('category_id');
    const catTxt = catEl.options[catEl.selectedIndex]?.text || '—';
    const price  = isPaid
        ? (document.getElementById('price').value || '0') + ' €'
        : 'Gratuit';

    document.getElementById('recap-title').textContent = title;
    document.getElementById('recap-cat').textContent   = catTxt;
    document.getElementById('recap-price').textContent = price;
}
</script>
@endsection