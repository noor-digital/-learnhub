    @extends('layouts.app')
@section('title', 'Erreur serveur')
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:2rem">
    <div style="text-align:center;max-width:420px">
        <div style="font-size:5rem;font-weight:800;color:#fcebeb;letter-spacing:-5px;line-height:1">
            500
        </div>
        <h1 style="font-size:1.4rem;font-weight:700;color:#1a1a2e;margin-bottom:.5rem">
            Erreur serveur
        </h1>
        <p style="color:#a8a5b8;font-size:.9rem;line-height:1.6;margin-bottom:1.5rem">
            Une erreur inattendue s'est produite. Nos équipes ont été notifiées.
        </p>
        <a href="{{ route('dashboard') }}"
           style="display:inline-flex;align-items:center;gap:6px;
                  background:#6c63ff;color:#fff;text-decoration:none;
                  padding:.65rem 1.4rem;border-radius:9px;font-weight:600;font-size:.9rem">
            <i class="bi bi-house"></i>Retour à l'accueil
        </a>
    </div>
</div>
@endsection