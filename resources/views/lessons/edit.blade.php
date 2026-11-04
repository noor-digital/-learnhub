@extends('layouts.app')
@section('title', 'Modifier la leçon')

@section('content')
<div class="container py-4" style="max-width:640px;">

    <nav class="mb-3" style="font-size:.82rem;">
        <a href="{{ route('courses.show', $course) }}"
           style="color:#6c63ff;text-decoration:none;">
            ← Retour au cours
        </a>
    </nav>

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h4 fw-bold mb-0">Modifier la leçon</h1>
            <p class="text-muted small mb-0">{{ $course->title }}</p>
        </div>

        <form method="POST"
              action="{{ route('lessons.destroy', [$course, $lesson]) }}"
              onsubmit="return confirm('Supprimer cette leçon ?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-outline-danger"
                    style="border-radius:7px;">
                <i class="bi bi-trash me-1"></i>Supprimer
            </button>
        </form>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4"
             style="border-radius:9px;border-left:4px solid #dc3545;font-size:.88rem;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('lessons.update', [$course, $lesson]) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold small">
                        Titre *
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $lesson->title) }}"
                           style="border-radius:8px;"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="video_url" class="form-label fw-semibold small">
                        URL vidéo
                        <span class="fw-normal text-muted">(YouTube ou direct)</span>
                    </label>
                    <input type="url"
                           id="video_url"
                           name="video_url"
                           class="form-control @error('video_url') is-invalid @enderror"
                           value="{{ old('video_url', $lesson->video_url) }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           style="border-radius:8px;">
                    @error('video_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($lesson->video_url)
                        <div class="mt-2 p-2 rounded-2"
                             style="background:#f8f7f4;border:1px solid #e8e6e0;
                                    font-size:.78rem;">
                            <span class="text-muted">URL actuelle : </span>
                            <a href="{{ $lesson->video_url }}"
                               target="_blank"
                               style="color:#6c63ff;word-break:break-all;">
                                {{ $lesson->video_url }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label for="duration" class="form-label fw-semibold small">
                            Durée (minutes)
                        </label>
                        <input type="number"
                               id="duration"
                               name="duration"
                               class="form-control @error('duration') is-invalid @enderror"
                               value="{{ old('duration', $lesson->duration) }}"
                               min="0"
                               style="border-radius:8px;">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="order" class="form-label fw-semibold small">
                            Ordre
                        </label>
                        <input type="number"
                               id="order"
                               name="order"
                               class="form-control @error('order') is-invalid @enderror"
                               value="{{ old('order', $lesson->order) }}"
                               min="1"
                               style="border-radius:8px;">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            class="btn fw-semibold px-4"
                            style="background:#6c63ff;color:#fff;
                                   border-radius:8px;border:none;">
                        <i class="bi bi-check-lg me-1"></i>
                        Enregistrer
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

    <div class="mt-3 p-3 rounded-2 text-muted"
         style="background:#f8f7f4;border:1px solid #e8e6e0;font-size:.78rem;">
        <i class="bi bi-clock me-1"></i>
        Créée le {{ $lesson->created_at->format('d/m/Y à H:i') }}
        @if($lesson->updated_at->gt($lesson->created_at))
            · Modifiée le {{ $lesson->updated_at->format('d/m/Y à H:i') }}
        @endif
    </div>

</div>
@endsection