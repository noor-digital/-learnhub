@extends('layouts.app')
@section('title', 'Modifier le cours')
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
.thumb-preview {
    max-width: 100%; max-height: 200px; border-radius: 8px;
    margin-bottom: 1rem; display: none;
}
.thumb-zone.has-preview .thumb-preview { display: block; }
.thumb-zone.has-preview .thumb-placeholder { display: none; }
.thumb-placeholder {
    color: #888; font-size: .9rem;
}
.thumb-placeholder i { font-size: 2rem; color: #ccc; margin-bottom: .5rem; }

/* ── Price toggle ─────────────────────────────────── */
.price-toggle {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 1rem;
}
.price-radio {
    display: flex; align-items: center; gap: 6px;
    cursor: pointer; font-size: .85rem; color: #666;
}
.price-radio input { accent-color: var(--brand); }
.price-fields {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
.price-fields.free { display: none; }

/* ── Publish toggle ───────────────────────────────── */
.publish-toggle {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1rem; background: #f8f7f4; border-radius: 8px;
    margin-bottom: 1rem;
}
.publish-toggle label {
    font-size: .85rem; font-weight: 500; color: #444; cursor: pointer;
    display: flex; align-items: center; gap: 8px; margin: 0;
}
.publish-toggle input { accent-color: var(--brand); }

/* ── Actions ──────────────────────────────────────── */
.w-actions {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 1.5rem; border-top: 1px solid #f0efe9;
}
.btn-main {
    padding: .75rem 1.5rem; border: none; border-radius: 9px;
    font-size: .9rem; font-weight: 600; cursor: pointer;
    transition: all .15s; text-decoration: none; display: inline-flex;
    align-items: center; gap: 8px;
}
.btn-primary { background: var(--brand); color: #fff; }
.btn-primary:hover { background: #5a52d5; transform: translateY(-1px); }
.btn-secondary {
    background: #f8f7f4; color: #666; border: 1px solid #e8e6e0;
}
.btn-secondary:hover { background: #f0efe9; }
.btn-danger {
    background: #dc3545; color: #fff;
}
.btn-danger:hover { background: #c82333; }
</style>

<div class="wizard-wrap">
    <div class="text-center mb-4">
        <h1 class="h3 fw-bold text-dark mb-2">Modifier le cours</h1>
        <p class="text-muted">Apportez des modifications à votre cours</p>
    </div>

    <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Step 1: Informations de base -->
        <div class="step-panel active" id="step-1">
            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon" style="background:#eeedfe;color:var(--brand);">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="w-card-title">Informations de base</h5>
                        <p class="w-card-desc">Titre, description et catégorie</p>
                    </div>
                </div>
                <div class="w-card-body">
                    <div class="mb-3">
                        <label class="lbl">Titre du cours <span>*</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $course->title) }}" required>
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="lbl">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Décrivez votre cours...">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="lbl">Catégorie <span>*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Choisir une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 2: Médias -->
            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon" style="background:#eaf3de;color:#3b6d11;">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h5 class="w-card-title">Image du cours</h5>
                        <p class="w-card-desc">Ajoutez une vignette attractive</p>
                    </div>
                </div>
                <div class="w-card-body">
                    <div class="thumb-zone {{ $course->thumbnail ? 'has-preview' : '' }}" onclick="document.getElementById('thumbnail').click()">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Preview" class="thumb-preview">
                        @endif
                        <div class="thumb-placeholder">
                            <i class="bi bi-cloud-upload"></i>
                            <div>Cliquez pour ajouter une image</div>
                            <small class="text-muted">JPG, PNG, WebP • Max 2MB</small>
                        </div>
                    </div>
                    <input type="file" id="thumbnail" name="thumbnail" class="d-none" accept="image/*">
                    @error('thumbnail')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Step 3: Prix et publication -->
            <div class="w-card">
                <div class="w-card-hd">
                    <div class="w-card-icon" style="background:#faeeda;color:#854f0b;">
                        <i class="bi bi-cash"></i>
                    </div>
                    <div>
                        <h5 class="w-card-title">Prix et publication</h5>
                        <p class="w-card-desc">Définissez le prix et publiez votre cours</p>
                    </div>
                </div>
                <div class="w-card-body">
                    <div class="price-toggle">
                        <label class="price-radio">
                            <input type="radio" name="price_type" value="free"
                                   {{ old('price', $course->price) == 0 ? 'checked' : '' }}
                                   onclick="togglePrice(false)">
                            Gratuit
                        </label>
                        <label class="price-radio">
                            <input type="radio" name="price_type" value="paid"
                                   {{ old('price', $course->price) > 0 ? 'checked' : '' }}
                                   onclick="togglePrice(true)">
                            Payant
                        </label>
                    </div>

                    <div class="price-fields {{ old('price', $course->price) == 0 ? 'free' : '' }}">
                        <div>
                            <label class="lbl">Prix (€)</label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', $course->price) }}" min="0" step="0.01">
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="publish-toggle">
                        <label>
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                            Publier le cours
                        </label>
                        <small class="text-muted">
                            {{ $course->is_published ? 'Visible par tous' : 'Brouillon' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="w-actions">
            <a href="{{ route('courses.show', $course) }}" class="btn-main btn-secondary">
                <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn-main btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
function togglePrice(isPaid) {
    const fields = document.querySelector('.price-fields');
    const priceInput = document.querySelector('input[name="price"]');
    if (isPaid) {
        fields.classList.remove('free');
        priceInput.required = true;
    } else {
        fields.classList.add('free');
        priceInput.required = false;
        priceInput.value = 0;
    }
}

// Gestion de la preview de l'image
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.thumb-preview');
            const zone = document.querySelector('.thumb-zone');
            preview.src = e.target.result;
            zone.classList.add('has-preview');
        };
        reader.readAsDataURL(file);
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const priceType = document.querySelector('input[name="price_type"]:checked');
    if (priceType) {
        togglePrice(priceType.value === 'paid');
    }
});
</script>
@endsection