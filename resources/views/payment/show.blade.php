@extends('layouts.app')
@section('title', 'Acheter le cours')
@section('content')
<div class="container py-5" style="max-width:520px">

    <div class="mb-4 text-center">
        <i class="bi bi-lock-fill" style="font-size:2.5rem;color:var(--brand)"></i>
        <h1 class="h4 fw-bold mt-2 mb-1">Cours payant</h1>
        <p class="text-muted" style="font-size:.88rem">
            Pour accéder à ce cours, vous devez l'acheter.
        </p>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">

        {{-- Aperçu cours --}}
        @if($course->thumbnail)
        <img src="{{ Storage::url($course->thumbnail) }}"
             style="width:100%;height:160px;object-fit:cover"
             alt="{{ $course->title }}">
        @endif

        <div class="p-4">
            <span class="badge mb-2"
                  style="background:#eeedfe;color:#534ab7;font-size:.75rem">
                {{ $course->category->name }}
            </span>
            <h2 class="h5 fw-bold mb-1" style="color:#1a1a2e">{{ $course->title }}</h2>
            <p class="text-muted mb-3" style="font-size:.83rem">
                <i class="bi bi-person me-1"></i>{{ $course->formateur->name }}
            </p>

            {{-- Prix --}}
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3"
                 style="background:#f8f7f4;border:1px solid #e8e6e0">
                <div>
                    <div class="text-muted" style="font-size:.78rem">Prix total</div>
                    <div class="fw-bold" style="font-size:1.9rem;color:var(--brand);line-height:1">
                        {{ number_format($course->price, 2, ',', ' ') }} €
                    </div>
                </div>
                <div class="ms-auto text-muted" style="font-size:.78rem">
                    <i class="bi bi-shield-check me-1 text-success"></i>Accès à vie<br>
                    <i class="bi bi-arrow-clockwise me-1 text-success"></i>Satisfait ou remboursé<br>
                    <i class="bi bi-award me-1 text-success"></i>Certificat inclus
                </div>
            </div>

            {{-- Simulation paiement --}}
            <div class="alert mb-3 py-2 px-3"
                 style="background:#faeeda;border:none;border-radius:8px;
                        font-size:.8rem;color:#633806">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Mode simulation</strong> — Aucun vrai paiement ne sera effectué.
            </div>

            <form method="POST" action="{{ route('payment.process', $course) }}">
                @csrf
                <button type="submit"
                        class="btn w-100 fw-semibold py-2"
                        style="background:var(--brand);color:#fff;
                               border-radius:10px;border:none;font-size:.95rem">
                    <i class="bi bi-credit-card me-2"></i>
                    Confirmer l'achat — {{ number_format($course->price, 2, ',', ' ') }} €
                </button>
            </form>

            <a href="{{ route('courses.show', $course) }}"
               class="d-block text-center mt-3 text-muted"
               style="font-size:.82rem;text-decoration:none">
                ← Retour au cours
            </a>
        </div>
    </div>
</div>
@endsection