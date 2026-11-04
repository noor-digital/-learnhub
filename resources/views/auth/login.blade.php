<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#140a35">
    <title>Connexion — LearnHub</title>
    <link rel="stylesheet" href="{{ asset('css/learnhub-login.css') }}">
</head>
<body class="lh-login-body">

    {{-- Icônes SVG locales (aucune dépendance externe) --}}
    <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden">
        <symbol id="lh-i-cap" viewBox="0 0 24 24"><path d="M22 9 12 4 2 9l10 5 10-5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/><path d="M22 9v6"/></symbol>
        <symbol id="lh-i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
        <symbol id="lh-i-lock" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
        <symbol id="lh-i-eye" viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></symbol>
        <symbol id="lh-i-eye-off" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="m1 1 22 22"/></symbol>
        <symbol id="lh-i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
        <symbol id="lh-i-check-circle" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></symbol>
        <symbol id="lh-i-check" viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></symbol>
    </svg>

    <div class="lh-login-page">

        {{-- Photographie de campus + voile indigo (décoratif). Image : public/images/auth/campus-login.jpg --}}
        <div class="lh-login-bg" aria-hidden="true"></div>

        <p class="lh-login-quote" aria-hidden="true">L'apprentissage ouvre des possibilités.</p>

        <main class="lh-login-card">

            <header class="lh-login-head">
                <a href="{{ url('/') }}" class="lh-login-logo" aria-label="LearnHub — accueil">
                    <span class="lh-login-logo__mark"><svg class="lh-login-icon" aria-hidden="true"><use href="#lh-i-cap"/></svg></span>
                    <span class="lh-login-logo__name">LearnHub</span>
                </a>
                <h1 class="lh-login-title">Bon retour parmi nous</h1>
                <p class="lh-login-sub">Connectez-vous pour reprendre votre apprentissage là où vous l'avez laissé.</p>
            </header>

            @if (session('status'))
                <div class="lh-login-alert lh-login-alert--success" role="status">
                    <svg class="lh-login-icon" aria-hidden="true"><use href="#lh-i-check-circle"/></svg>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="lh-login-alert lh-login-alert--error" role="alert">
                    <svg class="lh-login-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                    <div>
                        <p class="lh-login-alert__title">Connexion impossible</p>
                        <p>Vérifiez les informations saisies, puis réessayez.</p>
                    </div>
                </div>
            @endif

            {{-- Formulaire : méthode POST, route et noms de champs inchangés --}}
            <form method="POST" action="{{ route('login') }}" id="lh-login-form" novalidate>
                @csrf

                {{-- Email --}}
                <div class="lh-login-field">
                    <label class="lh-login-label" for="email">Adresse e-mail</label>
                    <div class="lh-login-control">
                        <svg class="lh-login-icon lh-login-control__icon" aria-hidden="true"><use href="#lh-i-mail"/></svg>
                        <input class="lh-login-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="vous@exemple.com"
                               autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false"
                               required
                               @if ($errors->has('email')) aria-invalid="true" aria-describedby="lh-login-email-error" @endif>
                    </div>
                    @error('email')
                        <p class="lh-login-error" id="lh-login-email-error">
                            <svg class="lh-login-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="lh-login-field">
                    <div class="lh-login-label-row">
                        <label class="lh-login-label" for="password">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a class="lh-login-link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                        @endif
                    </div>
                    <div class="lh-login-control">
                        <svg class="lh-login-icon lh-login-control__icon" aria-hidden="true"><use href="#lh-i-lock"/></svg>
                        <input class="lh-login-input lh-login-input--pwd {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               type="password" id="password" name="password"
                               placeholder="Votre mot de passe"
                               autocomplete="current-password"
                               required
                               @if ($errors->has('password')) aria-invalid="true" aria-describedby="lh-login-password-error" @endif>
                        <button type="button" class="lh-login-toggle" id="lh-login-toggle"
                                aria-label="Afficher le mot de passe" aria-pressed="false">
                            <svg class="lh-login-icon lh-login-icon--on" aria-hidden="true"><use href="#lh-i-eye"/></svg>
                            <svg class="lh-login-icon lh-login-icon--off" aria-hidden="true"><use href="#lh-i-eye-off"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="lh-login-error" id="lh-login-password-error">
                            <svg class="lh-login-icon" aria-hidden="true"><use href="#lh-i-alert"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Se souvenir de moi --}}
                <div class="lh-login-field lh-login-field--check">
                    <label class="lh-login-check">
                        <input class="lh-login-sr-only" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="lh-login-check__box" aria-hidden="true"><svg class="lh-login-icon"><use href="#lh-i-check"/></svg></span>
                        <span>Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit" class="lh-login-btn" id="lh-login-submit">
                    <span class="lh-login-btn__spinner" aria-hidden="true"></span>
                    <span class="lh-login-btn__label">Se connecter</span>
                </button>
            </form>

            @if (Route::has('register'))
                <p class="lh-login-switch">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}">Créer un compte</a>
                </p>
            @endif

        </main>
    </div>

    <script>
    (function () {
        var form   = document.getElementById('lh-login-form');
        var btn    = document.getElementById('lh-login-submit');
        var toggle = document.getElementById('lh-login-toggle');
        var pwd    = document.getElementById('password');
        if (!form || !btn) return;

        // Afficher / masquer le mot de passe
        if (toggle && pwd) {
            toggle.addEventListener('click', function () {
                var show = pwd.type === 'password';
                pwd.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                toggle.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                pwd.focus({ preventScroll: true });
            });
        }

        // État « chargement » : la soumission normale n'est jamais bloquée (sauf double clic)
        var label = btn.querySelector('.lh-login-btn__label');
        var idle  = label ? label.textContent : '';
        form.addEventListener('submit', function (e) {
            if (form.dataset.submitting === '1') { e.preventDefault(); return; }
            form.dataset.submitting = '1';
            btn.classList.add('is-loading');
            btn.setAttribute('aria-busy', 'true');
            if (label) label.textContent = 'Connexion en cours…';
        });
        // Retour arrière du navigateur (page restaurée depuis le cache)
        window.addEventListener('pageshow', function (ev) {
            if (!ev.persisted) return;
            form.dataset.submitting = '0';
            btn.classList.remove('is-loading');
            btn.removeAttribute('aria-busy');
            if (label) label.textContent = idle;
        });

        // Focus automatique sur grand écran uniquement (évite d'ouvrir le clavier sur mobile)
        if (window.matchMedia('(min-width: 960px)').matches) {
            var first = document.querySelector('.lh-login-input.is-invalid') || document.getElementById('email');
            if (first) first.focus();
        }
    })();
    </script>
</body>
</html>