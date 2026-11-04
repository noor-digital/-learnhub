@extends('layouts.app')
@section('title', 'Ajouter une leçon')

@section('content')
<div class="container py-4" style="max-width:640px;">

    <div class="mb-4">
        <a href="{{ route('courses.show', $course) }}"
           style="color:#6c63ff;text-decoration:none;font-size:.85rem;">
            <i class="bi bi-arrow-left me-1"></i>Retour au cours
        </a>
        <h1 class="h4 fw-bold mt-2 mb-0">Ajouter une leçon</h1>
        <p class="text-muted small">{{ $course->title }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius:9px;font-size:.88rem;">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('lessons.store', $course) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Titre *</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title') }}"
                           placeholder="Ex : Introduction à Laravel"
                           style="border-radius:8px;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        URL de la vidéo
                        <span class="fw-normal text-muted">(YouTube ou autre)</span>
                    </label>
                    <input type="url" name="video_url" class="form-control"
                           value="{{ old('video_url') }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           style="border-radius:8px;">
                    <div class="form-text">
                        Les URLs YouTube sont converties automatiquement en lecteur intégré.
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">
                            Durée (minutes)
                        </label>
                        <input type="number" name="duration" class="form-control"
                               value="{{ old('duration', 0) }}"
                               min="0" style="border-radius:8px;">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">
                            Ordre d'affichage
                        </label>
                        <input type="number" name="order" class="form-control"
                               value="{{ old('order') }}"
                               min="1"
                               placeholder="Auto si vide"
                               style="border-radius:8px;">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn fw-semibold px-4"
                            style="background:#6c63ff;color:#fff;
                                   border-radius:8px;border:none;">
                        Ajouter la leçon
                    </button>
                    <a href="{{ route('courses.show', $course) }}"
                       class="btn btn-outline-secondary"
                       style="border-radius:8px;">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection