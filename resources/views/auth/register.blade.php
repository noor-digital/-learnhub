<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#140a35">
    <title>Inscription — LearnHub</title>

    <link rel="preload" as="image" href="{{ asset('images/auth/campus-login.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/learnhub-register.css') }}?v={{ @filemtime(public_path('css/learnhub-register.css')) }}">
</head>
<body class="lh-register-body">

{{-- Icônes (sprite SVG intégré : la page ne dépend d'aucun layout) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="lh-i-cap" viewBox="0 0 24 24"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/></symbol>
    <symbol id="lh-i-user" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
    <symbol id="lh-i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
    <symbol id="lh-i-lock" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
    <symbol id="lh-i-eye" viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="lh-i-eye-off" viewBox="0 0 24 24"><path d="M17.94 17.94A10.9 10.9 0 0 1 12 19c-6.4 0-10-7-10-7a18.5 18.5 0 0 1 5.06-5.94"/><path d="M9.9 4.24A10.4 10.4 0 0 1 12 5c6.4 0 10 7 10 7a18.6 18.6 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="m1 1 22 22"/></symbol>
    <symbol id="lh-i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></symbol>
</svg>

<main class="lh-register-page {{ $errors->any() ? 'lh-register-page--alert' : '' }}">

    {{-- Photo plein écran (campus) : aucune colonne, aucun panneau --}}
    <div class="lh-register-bg" aria-hidden="true"></div>

    {{-- Phrase d'ambiance, grands écrans uniquement (supprimer cette ligne pour la retirer) --}}
    <p class="lh-register-quote" aria-hidden="true">Montez en compétences, une étape après l'autre.</p>

    {{-- Carte en verre dépoli, centrée horizontalement et verticalement --}}
    <section class="lh-register-card" aria-labelledby="lh-register-title">

        <header class="lh-register-head">
            <a class="lh-register-logo" href="{{ url('/') }}" aria-label="LearnHub — accueil">
                <span class="lh-register-logo__mark">
                    <svg class="lh-register-icon" aria-hidden="true"><use href="#lh-i-cap"/></svg>
                </span>
                <span class="lh-register-logo__name">LearnHub</span>
            </a>
            <h1 class="lh-register-title" id="lh-register-title">Créer un compte</h1>
            <p class="lh-register-sub">Quelques informations suffisent pour commencer.</p>
        </header>

        @if ($errors->any())
            <div class="lh-register-alert" role="alert">
                <svg class="lh-register-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                <div>
                    <p class="lh-register-alert__title">Inscription impossible</p>
                    <p>Corrigez les champs signalés ci-dessous, puis réessayez.</p>
                </div>
            </div>
        @endif

        <form class="lh-register-form" method="POST" action="{{ route('register') }}" data-auth-form novalidate>
            @csrf

            {{-- Nom --}}
            <div class="lh-register-field">
                <label class="lh-register-label" for="name">Nom complet</label>
                <div class="lh-register-control">
                    <svg class="lh-register-icon lh-register-control__icon" aria-hidden="true"><use href="#lh-i-user"/></svg>
                    <input class="lh-register-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           placeholder="Prénom Nom"
                           autocomplete="name"
                           required data-autofocus
                           @if ($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
                </div>
                @error('name')
                    <p class="lh-register-error" id="name-error">
                        <svg class="lh-register-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="lh-register-field">
                <label class="lh-register-label" for="email">Adresse e-mail</label>
                <div class="lh-register-control">
                    <svg class="lh-register-icon lh-register-control__icon" aria-hidden="true"><use href="#lh-i-mail"/></svg>
                    <input class="lh-register-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="vous@exemple.com"
                           autocomplete="email" inputmode="email" autocapitalize="none" spellcheck="false"
                           required
                           @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
                </div>
                @error('email')
                    <p class="lh-register-error" id="email-error">
                        <svg class="lh-register-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Mot de passe (la force s'affiche sur la ligne du libellé) --}}
            <div class="lh-register-field">
                <div class="lh-register-label-row">
                    <label class="lh-register-label" for="password">Mot de passe</label>
                    <div class="lh-register-meter" data-strength hidden>
                        <span class="lh-register-meter__bars" aria-hidden="true"><span></span><span></span><span></span><span></span></span>
                        <span class="lh-register-meter__text"></span>
                    </div>
                </div>
                <div class="lh-register-control">
                    <svg class="lh-register-icon lh-register-control__icon" aria-hidden="true"><use href="#lh-i-lock"/></svg>
                    <input class="lh-register-input lh-register-input--pwd {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           type="password" id="password" name="password"
                           placeholder="8 caractères minimum"
                           autocomplete="new-password"
                           required
                           @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                    <button type="button" class="lh-register-toggle" data-toggle-password="password"
                            aria-label="Afficher le mot de passe" aria-pressed="false">
                        <svg class="lh-register-icon lh-register-icon--on" aria-hidden="true"><use href="#lh-i-eye"/></svg>
                        <svg class="lh-register-icon lh-register-icon--off" aria-hidden="true"><use href="#lh-i-eye-off"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="lh-register-error" id="password-error">
                        <svg class="lh-register-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Confirmation (la correspondance s'affiche sur la ligne du libellé) --}}
            <div class="lh-register-field">
                <div class="lh-register-label-row">
                    <label class="lh-register-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <span class="lh-register-match" data-match hidden aria-live="polite"></span>
                </div>
                <div class="lh-register-control">
                    <svg class="lh-register-icon lh-register-control__icon" aria-hidden="true"><use href="#lh-i-lock"/></svg>
                    <input class="lh-register-input lh-register-input--pwd"
                           type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Répétez le mot de passe"
                           autocomplete="new-password"
                           required>
                    <button type="button" class="lh-register-toggle" data-toggle-password="password_confirmation"
                            aria-label="Afficher la confirmation du mot de passe" aria-pressed="false">
                        <svg class="lh-register-icon lh-register-icon--on" aria-hidden="true"><use href="#lh-i-eye"/></svg>
                        <svg class="lh-register-icon lh-register-icon--off" aria-hidden="true"><use href="#lh-i-eye-off"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="lh-register-btn" data-submit data-loading-label="Création du compte…">
                <span class="lh-register-btn__spinner" aria-hidden="true"></span>
                <span class="lh-register-btn__label">Créer mon compte</span>
            </button>
        </form>

        <p class="lh-register-switch">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}">Se connecter</a>
        </p>

    </section>
</main>

<script>
(function () {
    'use strict';

    var form = document.querySelector('[data-auth-form]');
    if (!form) return;

    /* ---- Afficher / masquer les mots de passe ---- */
    var showLabels = {
        password: 'Afficher le mot de passe',
        password_confirmation: 'Afficher la confirmation du mot de passe'
    };
    var hideLabels = {
        password: 'Masquer le mot de passe',
        password_confirmation: 'Masquer la confirmation du mot de passe'
    };
    Array.prototype.forEach.call(form.querySelectorAll('[data-toggle-password]'), function (btn) {
        var id = btn.getAttribute('data-toggle-password');
        var input = document.getElementById(id);
        if (!input) return;
        btn.addEventListener('click', function () {
            var reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            btn.setAttribute('aria-pressed', reveal ? 'true' : 'false');
            btn.setAttribute('aria-label', (reveal ? hideLabels : showLabels)[id] || '');
        });
    });

    /* ---- Force du mot de passe + correspondance ---- */
    var pwd   = document.getElementById('password');
    var conf  = document.getElementById('password_confirmation');
    var meter = form.querySelector('[data-strength]');
    var match = form.querySelector('[data-match]');

    if (pwd && conf && meter && match) {
        var text   = meter.querySelector('.lh-register-meter__text');
        var labels = ['', 'Faible', 'Moyen', 'Bon', 'Excellent'];

        var score = function (v) {
            if (v.length < 8) return 1;
            var s = 0;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            return Math.min(s + 1, 4);
        };

        var updateStrength = function () {
            var v = pwd.value;
            meter.hidden = !v;
            if (!v) return;
            var s = score(v);
            meter.setAttribute('data-level', s);
            text.textContent = labels[s];
        };

        var updateMatch = function () {
            if (!conf.value) { match.hidden = true; return; }
            var ok = conf.value === pwd.value;
            match.hidden = false;
            match.className = 'lh-register-match ' + (ok ? 'is-ok' : 'is-bad');
            match.textContent = ok ? 'Identiques' : 'Différents';
        };

        pwd.addEventListener('input', function () { updateStrength(); updateMatch(); });
        conf.addEventListener('input', updateMatch);
        updateStrength();
        updateMatch();
    }

    /* ---- Envoi : état de chargement, anti double-clic ---- */
    var submit = form.querySelector('[data-submit]');
    var label  = submit ? submit.querySelector('.lh-register-btn__label') : null;
    var idleLabel = label ? label.textContent : '';
    var busy = false;

    form.addEventListener('submit', function (e) {
        if (busy) { e.preventDefault(); return; }
        busy = true;
        if (submit) {
            submit.classList.add('is-loading');
            submit.setAttribute('aria-busy', 'true');
            if (label) label.textContent = submit.getAttribute('data-loading-label') || idleLabel;
        }
    });

    /* Retour arrière du navigateur (page restaurée depuis le cache) */
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        busy = false;
        if (submit) {
            submit.classList.remove('is-loading');
            submit.removeAttribute('aria-busy');
            if (label) label.textContent = idleLabel;
        }
    });

    /* ---- Focus : premier champ en erreur, sinon nom (souris/clavier seulement) ---- */
    var firstInvalid = form.querySelector('.is-invalid');
    var auto = form.querySelector('[data-autofocus]');
    var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (firstInvalid) {
        firstInvalid.focus({ preventScroll: true });
    } else if (auto && finePointer) {
        auto.focus({ preventScroll: true });
    }
})();
</script>
</body>
</html>