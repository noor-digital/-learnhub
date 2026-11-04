<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — LearnHub</title>
    <link rel="stylesheet" href="{{ asset('css/learnhub-auth.css') }}">
</head>
<body>

    {{-- Icônes SVG (sprite local, aucune dépendance externe) --}}
    <svg aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden">
        <symbol id="i-cap" viewBox="0 0 24 24"><path d="M22 9 12 4 2 9l10 5 10-5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/><path d="M22 9v6"/></symbol>
        <symbol id="i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
        <symbol id="i-lock" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
        <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></symbol>
        <symbol id="i-eye-off" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="m1 1 22 22"/></symbol>
        <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></symbol>
        <symbol id="i-check-circle" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></symbol>
        <symbol id="i-presentation" viewBox="0 0 24 24"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></symbol>
        <symbol id="i-layers" viewBox="0 0 24 24"><path d="m12 2 10 5-10 5L2 7l10-5z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/></symbol>
        <symbol id="i-trend" viewBox="0 0 24 24"><path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/></symbol>
        <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    </svg>

    <div class="auth">

        {{-- Panneau de marque : desktop uniquement (≥ 960px) --}}
        <aside class="auth-brand">
            <a href="{{ url('/') }}" class="logo logo--light" aria-label="LearnHub — accueil">
                <span class="logo__mark"><svg class="icon" aria-hidden="true"><use href="#i-cap"/></svg></span>
                <span class="logo__name">LearnHub</span>
            </a>

            <div>
                <p class="brand__title">@yield('brand_title')</p>
                <p class="brand__text">@yield('brand_text')</p>

                <div class="path" aria-hidden="true">
                    <div class="path__head">
                        <span class="path__title">Votre parcours</span>
                        <span class="path__badge">En cours</span>
                    </div>
                    <ol>
                        <li class="path__step is-done">
                            <span class="path__dot"><svg class="icon"><use href="#i-check"/></svg></span>
                            <span>
                                <span class="path__label">Découvrir les bases</span>
                                <span class="path__meta">Terminé</span>
                            </span>
                        </li>
                        <li class="path__step is-current">
                            <span class="path__dot"></span>
                            <span>
                                <span class="path__label">Pratiquer avec des exercices</span>
                                <span class="path__bar"><span></span></span>
                            </span>
                        </li>
                        <li class="path__step">
                            <span class="path__dot"></span>
                            <span>
                                <span class="path__label">Valider vos acquis</span>
                                <span class="path__meta">À venir</span>
                            </span>
                        </li>
                    </ol>
                </div>
            </div>

            <div>
                <ul class="benefits">
                    <li><svg class="icon" aria-hidden="true"><use href="#i-layers"/></svg>Des parcours structurés, étape par étape</li>
                    <li><svg class="icon" aria-hidden="true"><use href="#i-trend"/></svg>Un suivi clair de votre progression</li>
                    <li><svg class="icon" aria-hidden="true"><use href="#i-users"/></svg>Des cours conçus par des formateurs</li>
                </ul>
                <p class="brand__copy">© {{ now()->year }} LearnHub</p>
            </div>
        </aside>

        {{-- Zone formulaire --}}
        <main class="auth-main">
            {{-- Branding allégé : mobile et tablette --}}
            <header class="auth-topbar">
                <a href="{{ url('/') }}" class="logo" aria-label="LearnHub — accueil">
                    <span class="logo__mark"><svg class="icon" aria-hidden="true"><use href="#i-cap"/></svg></span>
                    <span class="logo__name">LearnHub</span>
                </a>
            </header>

            <div class="auth-center">
                @yield('content')
            </div>
        </main>

    </div>

    <script>
    (function () {
        // Afficher / masquer le mot de passe
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            var input = document.getElementById(btn.getAttribute('data-toggle-password'));
            if (!input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                input.focus({ preventScroll: true });
            });
        });

        // État "chargement" à l'envoi (la soumission normale du formulaire n'est jamais bloquée)
        document.querySelectorAll('form[data-auth-form]').forEach(function (form) {
            var btn   = form.querySelector('[data-submit]');
            var label = btn ? btn.querySelector('.btn__label') : null;
            var idle  = label ? label.textContent : '';

            form.addEventListener('submit', function (e) {
                if (form.dataset.submitting === '1') { e.preventDefault(); return; }
                form.dataset.submitting = '1';
                if (!btn) return;
                btn.classList.add('is-loading');
                btn.setAttribute('aria-busy', 'true');
                if (label && btn.dataset.loadingLabel) label.textContent = btn.dataset.loadingLabel;
            });

            // Retour arrière du navigateur (page restaurée depuis le cache)
            window.addEventListener('pageshow', function (ev) {
                if (!ev.persisted) return;
                form.dataset.submitting = '0';
                if (!btn) return;
                btn.classList.remove('is-loading');
                btn.removeAttribute('aria-busy');
                if (label) label.textContent = idle;
            });
        });

        // Focus automatique sur grand écran uniquement (évite d'ouvrir le clavier sur mobile)
        if (window.matchMedia('(min-width: 960px)').matches) {
            var first = document.querySelector('.input.is-invalid') || document.querySelector('[data-autofocus]');
            if (first) first.focus();
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>