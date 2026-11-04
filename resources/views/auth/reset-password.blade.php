<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouveau mot de passe — LearnHub</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{height:100%;overflow:hidden}
body{height:100%;overflow:hidden;font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#07030f;color:#1a1a2e;-webkit-text-size-adjust:100%}
img,svg{max-width:100%;display:block}
input,button{font:inherit}

:root{
    --p:#7c3aed;--p-dk:#5b21b6;--p-lt:#8b5cf6;
    --p-gl:rgba(124,58,237,.12);
    --acc:#06b6d4;--ok:#10b981;--err:#ef4444;--warn:#f59e0b;
    --t1:#0f0823;--t2:#374151;--t3:#6b7280;--t4:#9ca3af;
    --bg:#f5f2ff;--bg-i:#faf8ff;--border:#ede9fe;
    --r:16px;--r-sm:10px;
    --sh-card:0 0 0 1px rgba(124,58,237,.08),0 24px 64px rgba(124,58,237,.16),0 8px 24px rgba(0,0,0,.08),0 1px 0 rgba(255,255,255,.7) inset;
    --sh-btn:0 4px 18px rgba(124,58,237,.38),0 1px 0 rgba(255,255,255,.16) inset;
    --sh-btn-h:0 10px 32px rgba(124,58,237,.52),0 2px 0 rgba(255,255,255,.12) inset;
    --ease:cubic-bezier(.4,0,.2,1);
    --spring:cubic-bezier(.22,1,.36,1);
}

.auth-root{display:flex;align-items:stretch;height:100vh;overflow:hidden;padding:18px;gap:18px;background:linear-gradient(135deg,#07030f 0%,#0f0520 100%)}

/* Hero */
.hero{flex:0 0 46%;min-width:0;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;padding:2.5rem 2.25rem;border-radius:var(--r);background:linear-gradient(148deg,#090320 0%,#13073e 38%,#080e38 72%,#040214 100%);isolation:isolate}
.orb{position:absolute;border-radius:50%;pointer-events:none}
.o1{width:360px;height:360px;background:radial-gradient(circle,rgba(124,58,237,.45) 0%,transparent 70%);filter:blur(52px);top:-85px;left:-75px;animation:oa 12s ease-in-out infinite}
.o2{width:270px;height:270px;background:radial-gradient(circle,rgba(16,185,129,.28) 0%,transparent 70%);filter:blur(46px);bottom:-65px;right:-52px;animation:ob 10s ease-in-out infinite;animation-delay:-5s}
.o3{width:150px;height:150px;background:radial-gradient(circle,rgba(6,182,212,.24) 0%,transparent 70%);filter:blur(30px);top:52%;left:52%;transform:translate(-50%,-50%);animation:oc 8s ease-in-out infinite;animation-delay:-2.5s}
@keyframes oa{0%,100%{transform:scale(1) translate(0,0)}33%{transform:scale(1.08) translate(-12px,-16px)}66%{transform:scale(.96) translate(8px,7px)}}
@keyframes ob{0%,100%{transform:scale(1) translate(0,0)}50%{transform:scale(1.1) translate(-10px,-12px)}}
@keyframes oc{0%,100%{transform:translate(-50%,-50%) scale(1)}50%{transform:translate(-50%,-50%) scale(1.35)}}
.h-dots{position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.054) 1px,transparent 1px);background-size:26px 26px;pointer-events:none;mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 40%,transparent 100%)}
.hero::before{content:'';position:absolute;top:0;left:-40%;width:80%;height:1px;background:linear-gradient(90deg,transparent,rgba(124,58,237,.5) 40%,rgba(16,185,129,.4) 60%,transparent);transform:rotate(-20deg) translateY(90px);animation:ll 6s ease-in-out infinite;pointer-events:none;z-index:1}
@keyframes ll{0%,100%{opacity:.4;transform:rotate(-20deg) translateY(90px)}50%{opacity:.8;transform:rotate(-20deg) translateY(72px)}}
.h-inner{position:relative;z-index:2;width:100%;max-width:372px;text-align:center;overflow-wrap:break-word}
.h-brand{display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:2rem;transition:transform .22s var(--spring)}
.h-brand:hover{transform:translateY(-2px)}
.h-icon{flex-shrink:0;width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--p),var(--acc));display:flex;align-items:center;justify-content:center;font-size:1.2rem;box-shadow:0 6px 20px rgba(124,58,237,.45),0 1px 0 rgba(255,255,255,.15) inset;transition:transform .26s var(--spring),box-shadow .22s}
.h-brand:hover .h-icon{transform:rotate(-6deg) scale(1.1)}
.h-name{font-size:1.3rem;font-weight:800;color:#fff;letter-spacing:-.2px;white-space:nowrap}
.h-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.24);border-radius:999px;padding:.28rem .9rem;font-size:.67rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#6ee7b7;margin-bottom:.75rem;animation:pulse-ok 3s ease-in-out infinite}
@keyframes pulse-ok{0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.24)}50%{box-shadow:0 0 0 5px rgba(16,185,129,0)}}
.h-tag{font-size:.67rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.32);margin-bottom:.55rem}
.h-title{font-size:clamp(1.4rem,2vw,1.95rem);font-weight:800;color:#fff;line-height:1.17;letter-spacing:-.4px;margin-bottom:.82rem;word-break:break-word}
.h-title em{background:linear-gradient(125deg,#c4b5fd 0%,#6ee7b7 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:normal}
.h-desc{font-size:.86rem;color:rgba(255,255,255,.44);line-height:1.75;max-width:290px;margin:0 auto 1.75rem}
.h-steps{display:flex;flex-direction:column;gap:.6rem}
.h-step{display:flex;align-items:center;gap:.75rem;background:rgba(255,255,255,.065);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:.7rem .9rem;transition:background .22s}
.h-step:hover{background:rgba(255,255,255,.09)}
.h-step-num{flex-shrink:0;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#fff}
.h-step-txt{font-size:.79rem;color:rgba(255,255,255,.58)}
.h-step.active{border-color:rgba(16,185,129,.3);background:rgba(16,185,129,.08)}
.h-step.active .h-step-txt{color:rgba(255,255,255,.78)}

/* Panel */
.panel{flex:1 1 0;min-width:0;display:flex;align-items:center;justify-content:center;background:var(--bg);border-radius:var(--r);border-left:1px solid rgba(124,58,237,.1);overflow-y:auto;overflow-x:hidden;padding:1.5rem;scrollbar-width:thin;scrollbar-color:transparent transparent;transition:scrollbar-color .25s}
.panel:hover{scrollbar-color:rgba(124,58,237,.28) transparent}
.panel::-webkit-scrollbar{width:4px}
.panel::-webkit-scrollbar-thumb{background:transparent;border-radius:999px}
.panel:hover::-webkit-scrollbar-thumb{background:rgba(124,58,237,.28)}

/* Card */
.card{width:100%;max-width:420px;background:#fff;border-radius:var(--r);border:1px solid rgba(124,58,237,.1);box-shadow:var(--sh-card);padding:2rem 2.1rem 1.75rem;position:relative;overflow:hidden;min-width:0;word-break:break-word;overflow-wrap:break-word;animation:card-rise .5s var(--spring) both}
@keyframes card-rise{from{opacity:0;transform:translateY(18px) scale(.985);filter:blur(2px)}to{opacity:1;transform:translateY(0) scale(1);filter:blur(0)}}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:2.5px;background:linear-gradient(90deg,var(--p) 0%,var(--p-lt) 50%,var(--ok) 100%);border-radius:var(--r) var(--r) 0 0}
.card::after{content:'';position:absolute;top:0;left:0;right:0;height:35%;background:linear-gradient(180deg,rgba(255,255,255,.5) 0%,rgba(255,255,255,.02) 100%);pointer-events:none;border-radius:var(--r) var(--r) 0 0}
.card-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,rgba(124,58,237,.1),rgba(16,185,129,.1));border:1px solid rgba(124,58,237,.16);display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:1.1rem;animation:icon-bounce .6s .15s var(--spring) both}
@keyframes icon-bounce{from{opacity:0;transform:scale(.7) translateY(8px)}to{opacity:1;transform:scale(1) translateY(0)}}
.card-eyebrow{display:inline-flex;align-items:center;gap:5px;font-size:.65rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--p);margin-bottom:.4rem}
.card-title{font-size:1.45rem;font-weight:800;color:var(--t1);letter-spacing:-.35px;margin-bottom:.4rem;line-height:1.15}
.card-desc{font-size:.84rem;color:var(--t3);line-height:1.65;margin-bottom:1.35rem;max-width:340px}

/* Alertes */
.alert{display:flex;align-items:flex-start;gap:9px;border-radius:10px;padding:.72rem .9rem;font-size:.81rem;margin-bottom:1rem;animation:shake .3s var(--ease)}
@keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-4px)}75%{transform:translateX(4px)}}
.alert-ok{background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(16,185,129,.04));border:1px solid rgba(16,185,129,.2);border-left:3px solid var(--ok);color:#065f46}
.alert-ok i{color:var(--ok);flex-shrink:0;margin-top:1px;font-size:1rem}
.alert-err{background:#fef2f2;border:1px solid #fecaca;border-left:3px solid var(--err);color:#7f1d1d}
.alert-err i{color:var(--err);flex-shrink:0;margin-top:1px}

/* Champs */
.field{margin-bottom:.85rem;width:100%}
.f-lbl{display:block;font-size:.75rem;font-weight:600;color:var(--t2);margin-bottom:5px;letter-spacing:.1px}
.f-lbl .req{color:var(--err);margin-left:1px}
.f-wrap{position:relative;width:100%}
.f-ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#b0a8d0;font-size:.9rem;pointer-events:none;z-index:1;transition:color .2s,transform .22s var(--spring)}
.f-wrap:focus-within .f-ico{color:var(--p);transform:translateY(-50%) scale(1.12)}
.f-inp{display:block;width:100%;padding:.74rem 2.7rem .74rem 2.45rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.88rem;color:var(--t1);background:var(--bg-i);outline:none;-webkit-appearance:none;transition:border-color .2s var(--ease),box-shadow .2s var(--ease),background .2s,transform .15s var(--ease);box-sizing:border-box}
.f-inp::placeholder{color:#c8c0e8}
.f-inp:hover{border-color:rgba(124,58,237,.35);background:#fff}
.f-inp:focus{border-color:var(--p);background:#fff;box-shadow:0 0 0 3.5px rgba(124,58,237,.13),0 4px 12px rgba(124,58,237,.1);transform:translateY(-1px)}
.f-inp.is-err{border-color:var(--err);background:#fef2f2;box-shadow:0 0 0 3px rgba(239,68,68,.1)}
.f-inp.is-ok{border-color:var(--ok);box-shadow:0 0 0 3px rgba(16,185,129,.09)}
.p-tog{position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#b0a8d0;font-size:.9rem;padding:3px;transition:color .18s,transform .2s var(--spring);line-height:1;z-index:1}
.p-tog:hover{color:var(--p);transform:translateY(-50%) scale(1.15)}
.f-err{display:flex;align-items:center;gap:4px;font-size:.71rem;color:var(--err);margin-top:4px;animation:fe .18s var(--ease)}
@keyframes fe{from{opacity:0;transform:translateY(-3px)}to{opacity:1;transform:translateY(0)}}
.pwd-str{margin-top:5px}
.pwd-bars{display:flex;gap:3px;height:3px;margin-bottom:3px}
.pwd-bar{flex:1;border-radius:999px;background:var(--border);transition:background .35s}
.bw{background:var(--err)}.bf{background:var(--warn)}.bg{background:#3b82f6}.bs{background:var(--ok)}
.pwd-hint{font-size:.68rem;font-weight:500;transition:color .3s}
.pmatch{display:none;align-items:center;gap:4px;font-size:.7rem;margin-top:3px}
.pmatch.ok{display:flex;color:var(--ok)}.pmatch.er{display:flex;color:var(--err)}

/* CTA */
.btn-cta{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:.85rem 1rem;background:linear-gradient(135deg,#6d28d9 0%,#7c3aed 40%,#8b5cf6 80%,#06b6d4 100%);background-size:200% 200%;animation:grad-sh 4s ease infinite;color:#fff;border:none;border-radius:var(--r-sm);font-size:.91rem;font-weight:700;cursor:pointer;font-family:inherit;letter-spacing:.1px;box-shadow:var(--sh-btn);transition:transform .2s var(--spring),box-shadow .2s var(--ease),filter .2s;position:relative;overflow:hidden;box-sizing:border-box;margin-top:.15rem}
@keyframes grad-sh{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.btn-cta::before{content:'';position:absolute;top:0;left:-120%;width:65%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent);transform:skewX(-14deg);transition:left .6s var(--ease)}
.btn-cta:hover::before{left:140%}
.btn-cta::after{content:'';position:absolute;top:0;left:0;right:0;height:50%;background:linear-gradient(180deg,rgba(255,255,255,.16) 0%,transparent 100%);pointer-events:none}
.btn-cta:hover{transform:translateY(-2px);box-shadow:var(--sh-btn-h);filter:brightness(1.04)}
.btn-cta:active{transform:scale(.98) translateY(0)}
.btn-cta:disabled,.btn-cta[disabled]{opacity:.6;cursor:not-allowed;transform:none;animation:none;box-shadow:var(--sh-btn)}
.btn-arr{transition:transform .2s var(--spring)}
.btn-cta:not(.loading):hover .btn-arr{transform:translateX(5px)}
.spin{display:none;flex-shrink:0;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:sp .65s linear infinite}
.btn-cta.loading .spin{display:block}
.btn-cta.loading .btn-lbl{opacity:.75}
.btn-cta.loading{pointer-events:none;cursor:wait}
@keyframes sp{to{transform:rotate(360deg)}}

.back-link{display:inline-flex;align-items:center;gap:5px;font-size:.81rem;color:var(--t3);text-decoration:none;margin-top:1.1rem;transition:color .18s,transform .18s var(--spring)}
.back-link:hover{color:var(--p);transform:translateX(-3px)}
.card-foot{text-align:center;font-size:.8rem;color:var(--t4);margin-top:1rem}
.card-foot a{color:var(--p);font-weight:600;text-decoration:none;transition:color .18s}
.card-foot a:hover{color:var(--p-dk)}
.sec-note{display:flex;align-items:flex-start;gap:8px;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.16);border-radius:9px;padding:.72rem .85rem;font-size:.77rem;color:#065f46;margin-top:1rem}
.sec-note i{color:var(--ok);flex-shrink:0;margin-top:1px;font-size:.9rem}

/* Tablette */
@media(max-width:1024px) and (min-width:769px){.auth-root{padding:14px;gap:14px}.hero{flex:0 0 42%;padding:2.25rem 1.75rem}.panel{padding:1.25rem}.card{padding:1.75rem 1.75rem 1.5rem}}

/* Mobile */
@media(max-width:768px){
    html,body{height:auto;overflow:auto}
    body{background:linear-gradient(160deg,#0a0418 0%,#110840 55%,#060d24 100%)}
    .hero{display:none!important}
    .auth-root{flex-direction:column;height:auto;min-height:100dvh;padding:0;gap:0;justify-content:center;align-items:center}
    .panel{flex:1 1 auto;min-height:100dvh;width:100%;padding:max(24px,env(safe-area-inset-top)) 18px max(28px,env(safe-area-inset-bottom));align-items:center;justify-content:center;overflow-y:auto;overflow-x:hidden;background:transparent;border-radius:0;border-left:none}
    .mob-brand{display:flex!important;align-items:center;gap:9px;text-decoration:none;margin-bottom:1.5rem}
    .mob-brand-name{font-size:1.18rem;font-weight:800;color:#fff;letter-spacing:-.2px}
    .card{max-width:100%;padding:1.75rem 1.4rem 1.5rem;border-radius:22px;animation:none;background:rgba(255,255,255,.94);backdrop-filter:blur(28px) saturate(180%);-webkit-backdrop-filter:blur(28px) saturate(180%);box-shadow:0 32px 64px rgba(124,58,237,.18),0 8px 24px rgba(0,0,0,.14),0 1px 0 rgba(255,255,255,.55) inset;border:1px solid rgba(255,255,255,.72)}
    .card-title{font-size:1.32rem}
    .f-inp{font-size:max(.88rem,16px)}
    .btn-cta{padding:.86rem 1rem}
}
@media(max-width:375px){.panel{padding-left:14px;padding-right:14px}.card{padding:1.5rem 1.2rem 1.35rem;border-radius:20px}.card-title{font-size:1.2rem}}
@media(max-width:320px){.card{padding:1.35rem 1rem 1.2rem}.card-title{font-size:1.08rem}.f-inp{font-size:16px}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>

<div class="auth-root">

    {{-- ══ HERO ══ --}}
    <div class="hero">
        <div class="h-dots"></div>
        <div class="orb o1"></div>
        <div class="orb o2"></div>
        <div class="orb o3"></div>
        <div class="h-inner">
            <a href="/" class="h-brand">
                <div class="h-icon"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
                <span class="h-name">LearnHub</span>
            </a>
            <div class="h-badge"><i class="bi bi-shield-check"></i> Réinitialisation sécurisée</div>
            <p class="h-tag">Nouvelle clé d'accès</p>
            <h1 class="h-title">Choisissez un mot de passe <em>fort</em>.</h1>
            <p class="h-desc">Créez un mot de passe robuste pour sécuriser votre compte et protéger votre progression d'apprentissage.</p>
            <div class="h-steps">
                @php
                $steps = [
                    ['Demande envoyée','rgba(16,185,129,.2)','#6ee7b7',true],
                    ['Lien reçu par email','rgba(16,185,129,.2)','#6ee7b7',true],
                    ['Nouveau mot de passe','rgba(124,58,237,.2)','#c4b5fd',true],
                    ['Accès restauré','rgba(255,255,255,.08)','rgba(255,255,255,.4)',false],
                ];
                @endphp
                @foreach($steps as $i => $s)
                <div class="h-step {{ $s[3] ? 'active' : '' }}">
                    <div class="h-step-num" style="background:{{ $s[1] }};color:{{ $s[2] }}">{{ $i + 1 }}</div>
                    <div class="h-step-txt">{{ $s[0] }}</div>
                    @if($s[3])<i class="bi bi-check-circle-fill" style="margin-left:auto;color:#6ee7b7;font-size:.82rem"></i>@endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ PANEL ══ --}}
    <div class="panel">

        <a href="/" class="mob-brand" style="display:none">
            <div class="h-icon" style="width:36px;height:36px;font-size:1rem;border-radius:9px">
                <i class="bi bi-mortarboard-fill" style="color:#fff"></i>
            </div>
            <span class="mob-brand-name">LearnHub</span>
        </a>

        <div class="card">

            <div class="card-icon">🔑</div>
            <div class="card-eyebrow"><i class="bi bi-key"></i> Nouveau mot de passe</div>
            <h2 class="card-title">Choisissez votre mot de passe</h2>
            <p class="card-desc">Créez un mot de passe fort d'au moins 8 caractères. Mélangez majuscules, chiffres et symboles.</p>

            @if($errors->any())
            <div class="alert alert-err" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            {{--
                FORMULAIRE RESET PASSWORD :
                ✅ method="POST"
                ✅ action="{{ route('password.store') }}"   → POST /reset-password
                ✅ @csrf
                ✅ name="token"    (hidden — CRITIQUE)
                ✅ name="email"
                ✅ name="password"
                ✅ name="password_confirmation"
                ✅ type="submit"

                ⚠️  La route GET /reset-password/{token} affiche ce formulaire
                    La route POST /reset-password soumet le formulaire
                    Ces deux routes sont DIFFÉRENTES — aucune confusion possible.
            --}}
            <form
                method="POST"
                action="{{ route('password.store') }}"
                id="rp-form"
                novalidate
            >
                @csrf

                {{-- Token OBLIGATOIRE — lu depuis l'URL via $request->route('token') --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div class="field">
                    <label class="f-lbl" for="email">
                        Adresse email<span class="req">*</span>
                    </label>
                    <div class="f-wrap">
                        <i class="bi bi-envelope f-ico" aria-hidden="true"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="f-inp {{ $errors->has('email') ? 'is-err' : '' }}"
                            value="{{ old('email', $request->email) }}"
                            placeholder="vous@exemple.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                    <div class="f-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="field">
                    <label class="f-lbl" for="password">
                        Nouveau mot de passe<span class="req">*</span>
                    </label>
                    <div class="f-wrap">
                        <i class="bi bi-lock f-ico" aria-hidden="true"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="f-inp {{ $errors->has('password') ? 'is-err' : '' }}"
                            placeholder="8 caractères minimum"
                            autocomplete="new-password"
                            required
                            oninput="chkStr(this.value); chkMatch();"
                        >
                        <button type="button" class="p-tog"
                                onclick="tPwd('password','ip1')"
                                aria-label="Afficher/masquer">
                            <i class="bi bi-eye-slash" id="ip1"></i>
                        </button>
                    </div>
                    <div class="pwd-str">
                        <div class="pwd-bars">
                            <div class="pwd-bar" id="b1"></div>
                            <div class="pwd-bar" id="b2"></div>
                            <div class="pwd-bar" id="b3"></div>
                            <div class="pwd-bar" id="b4"></div>
                        </div>
                        <div class="pwd-hint" id="ph"></div>
                    </div>
                    @error('password')
                    <div class="f-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div class="field">
                    <label class="f-lbl" for="password_confirmation">
                        Confirmer le mot de passe<span class="req">*</span>
                    </label>
                    <div class="f-wrap">
                        <i class="bi bi-lock-fill f-ico" aria-hidden="true"></i>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="f-inp"
                            placeholder="Répétez votre mot de passe"
                            autocomplete="new-password"
                            required
                            oninput="chkMatch()"
                        >
                        <button type="button" class="p-tog"
                                onclick="tPwd('password_confirmation','ip2')"
                                aria-label="Afficher/masquer">
                            <i class="bi bi-eye-slash" id="ip2"></i>
                        </button>
                    </div>
                    <div class="pmatch" id="pm">
                        <i class="bi bi-check-circle-fill"></i> Les mots de passe correspondent
                    </div>
                </div>

                <button type="submit" class="btn-cta" id="rp-btn">
                    <span class="spin" aria-hidden="true"></span>
                    <span class="btn-lbl">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Réinitialiser le mot de passe
                    </span>
                    <i class="bi bi-arrow-right btn-arr" aria-hidden="true"></i>
                </button>

            </form>

            <div class="sec-note">
                <i class="bi bi-info-circle-fill"></i>
                <span>Après la réinitialisation, vous serez redirigé vers la page de connexion.</span>
            </div>

            <div style="text-align:center;margin-top:1.1rem">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Retour à la connexion
                </a>
            </div>

            <div class="card-foot">
                Besoin d'aide ? <a href="{{ route('password.request') }}">Renvoyer un lien →</a>
            </div>

        </div>
    </div>

</div>

<script>
/* ── Force mot de passe ── */
function chkStr(v) {
    var b = [1,2,3,4].map(function(n){ return document.getElementById('b'+n); });
    var h = document.getElementById('ph');
    b.forEach(function(x){ x.className = 'pwd-bar'; });
    if (!v) { if(h) h.textContent = ''; return; }
    var s = 0;
    if (v.length >= 8)            s++;
    if (/[A-Z]/.test(v))         s++;
    if (/[0-9]/.test(v))         s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    var cls = ['','bw','bf','bg','bs'];
    var txt = ['','Trop faible','Faible','Moyen','Fort 🔒'];
    var col = ['','#ef4444','#f59e0b','#3b82f6','#10b981'];
    b.slice(0,s).forEach(function(x){ x.classList.add(cls[s]); });
    if (h) { h.textContent = txt[s]; h.style.color = col[s]; }
}

/* ── Correspondance ── */
function chkMatch() {
    var p1 = document.getElementById('password');
    var p2 = document.getElementById('password_confirmation');
    var el = document.getElementById('pm');
    if (!p1||!p2||!el) return;
    if (!p2.value) { el.className='pmatch'; p2.classList.remove('is-ok','is-err'); return; }
    if (p1.value === p2.value) {
        el.className='pmatch ok';
        el.innerHTML='<i class="bi bi-check-circle-fill"></i> Les mots de passe correspondent';
        p2.classList.add('is-ok'); p2.classList.remove('is-err');
    } else {
        el.className='pmatch er';
        el.innerHTML='<i class="bi bi-x-circle-fill"></i> Ne correspondent pas';
        p2.classList.add('is-err'); p2.classList.remove('is-ok');
    }
}

/* ── Toggle password ── */
function tPwd(id, ico) {
    var i = document.getElementById(id);
    var ic = document.getElementById(ico);
    if (!i||!ic) return;
    i.type = i.type==='password'?'text':'password';
    ic.className = i.type==='password'?'bi bi-eye-slash':'bi bi-eye';
}

/* ── Spinner corrigé ── */
(function () {
    var form = document.getElementById('rp-form');
    var btn  = document.getElementById('rp-btn');
    if (!form || !btn) return;

    /* Retire loading au retour (bfcache) */
    window.addEventListener('pageshow', function () {
        btn.classList.remove('loading');
        btn.disabled = false;
    });

    /* Retire loading si erreurs présentes au chargement */
    @if($errors->any())
    btn.classList.remove('loading');
    btn.disabled = false;
    @endif

    form.addEventListener('submit', function (e) {
        var ok = true;

        /* Vérifie email */
        var email = document.getElementById('email');
        if (email && !email.value.trim()) {
            email.classList.add('is-err'); email.focus(); ok = false;
        }

        /* Vérifie mot de passe */
        var pwd = document.getElementById('password');
        if (pwd && pwd.value.length < 8) {
            pwd.classList.add('is-err'); if (ok) pwd.focus(); ok = false;
        }

        /* Vérifie correspondance */
        var conf = document.getElementById('password_confirmation');
        if (pwd && conf && pwd.value !== conf.value) {
            conf.classList.add('is-err'); if (ok) conf.focus(); ok = false;
        }

        if (!ok) {
            e.preventDefault();
            return;
        }

        /* Spinner visuel */
        btn.classList.add('loading');
        btn.disabled = true;

        /* Timeout de sécurité 15s */
        setTimeout(function () {
            btn.classList.remove('loading');
            btn.disabled = false;
        }, 15000);
    });

    /* Reset visuel des erreurs à la saisie */
    ['email','password','password_confirmation'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('input', function() {
            this.classList.remove('is-err');
        });
    });
})();
</script>
</body>
</html>