<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name','LearnHub') }} — @yield('title','Catalogue')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{
    --brand:#6c63ff;--brand-dk:#5a52d5;--navy:#1a1a2e;
    --card-r:12px;--nav-h:60px;
}
*{box-sizing:border-box}
body{background:#f8f7f4;font-family:'Segoe UI',system-ui,sans-serif;margin:0;padding-top:var(--nav-h)}

/* ── Navbar ── */
.lms-nav{position:fixed;top:0;left:0;right:0;height:var(--nav-h);background:var(--navy);
    display:flex;align-items:center;justify-content:space-between;
    padding:0 1.5rem;z-index:100;box-shadow:0 2px 16px rgba(0,0,0,.18)}
.lms-brand{color:#fff;font-weight:700;font-size:1.1rem;text-decoration:none;
    display:flex;align-items:center;gap:8px}
.lms-brand:hover{color:#c0bfe0}
.lms-links{display:flex;align-items:center;gap:4px}
.lms-link{color:#c0bfe0;font-size:.875rem;padding:.4rem .85rem;border-radius:7px;
    text-decoration:none;transition:background .15s,color .15s;white-space:nowrap}
.lms-link:hover,.lms-link.on{background:rgba(255,255,255,.12);color:#fff}
.lms-create{background:var(--brand);color:#fff!important;font-weight:500;
    padding:.4rem 1rem;border-radius:7px;transition:background .15s}
.lms-create:hover{background:var(--brand-dk)}

/* ── Burger ── */
.burger{display:none;flex-direction:column;gap:5px;background:transparent;
    border:none;cursor:pointer;padding:6px;border-radius:6px}
.burger:hover{background:rgba(255,255,255,.1)}
.burger span{display:block;width:22px;height:2px;background:#fff;border-radius:2px;
    transition:transform .3s,opacity .3s}
.burger.on span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.burger.on span:nth-child(2){opacity:0}
.burger.on span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
@media(max-width:768px){.burger{display:flex}.lms-links{display:none}}

/* ── Overlay ── */
#ov{position:fixed;inset:0;background:rgba(0,0,0,.52);backdrop-filter:blur(3px);
    z-index:200;opacity:0;pointer-events:none;transition:opacity .3s}
#ov.on{opacity:1;pointer-events:all}

/* ── Mobile menu ── */
#mob{position:fixed;top:0;left:0;width:290px;height:100%;background:#fff;
    z-index:300;transform:translateX(-100%);
    transition:transform .32s cubic-bezier(.4,0,.2,1);
    display:flex;flex-direction:column;overflow:hidden}
#mob.on{transform:translateX(0);box-shadow:8px 0 40px rgba(0,0,0,.18)}
.mob-hd{background:var(--navy);padding:1.1rem 1.1rem .9rem;flex-shrink:0}
.mob-hd-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:.85rem}
.mob-brand{color:#fff;font-weight:700;font-size:1rem;display:flex;align-items:center;gap:7px}
.mob-x{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.12);
    border:none;color:#fff;font-size:1.05rem;display:flex;align-items:center;
    justify-content:center;cursor:pointer}
.mob-x:hover{background:rgba(255,255,255,.22)}
.u-av{width:40px;height:40px;border-radius:50%;background:var(--brand);color:#fff;
    font-size:.8rem;font-weight:700;display:flex;align-items:center;justify-content:center;
    flex-shrink:0;border:2px solid rgba(255,255,255,.2)}
.u-nm{color:#fff;font-size:.86rem;font-weight:600;white-space:nowrap;
    overflow:hidden;text-overflow:ellipsis;max-width:170px}
.u-em{color:#9b9bc4;font-size:.72rem}
.mob-body{flex:1;overflow-y:auto;padding:.6rem .6rem}
.mob-lbl{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;
    color:#b4b2a9;padding:.4rem .6rem .2rem;margin-top:.2rem}
.mi{display:flex;align-items:center;gap:11px;padding:.65rem .8rem;border-radius:9px;
    text-decoration:none;color:#2c2c2a;font-size:.875rem;
    transition:background .14s,transform .1s;margin-bottom:2px}
.mi:hover{background:#f0efe9;color:#1a1a2e;transform:translateX(2px)}
.mi.on{background:#eeedfe;color:var(--brand);font-weight:600}
.mi-ic{width:33px;height:33px;border-radius:8px;display:flex;align-items:center;
    justify-content:center;font-size:.9rem;flex-shrink:0}
.ic-p{background:#eeedfe;color:var(--brand)}
.ic-t{background:#e1f5ee;color:#0f6e56}
.ic-a{background:#faeeda;color:#854f0b}
.ic-g{background:#eaf3de;color:#3b6d11}
.ic-r{background:#fcebeb;color:#a32d2d}
.mob-ft{padding:.65rem;border-top:1px solid #f0efe9;flex-shrink:0}
.btn-lo{display:flex;align-items:center;gap:10px;width:100%;padding:.65rem .8rem;
    border-radius:9px;background:#fcebeb;border:none;color:#a32d2d;
    font-size:.875rem;font-weight:500;cursor:pointer;transition:background .15s}
.btn-lo:hover{background:#f7c1c1}

/* ── Toasts ── */
#toast-wrap{position:fixed;top:calc(var(--nav-h) + 12px);right:16px;z-index:9999;
    display:flex;flex-direction:column;gap:8px;pointer-events:none}
.toast-item{display:flex;align-items:center;gap:10px;padding:.7rem 1rem;
    border-radius:10px;font-size:.85rem;font-weight:500;min-width:260px;max-width:340px;
    box-shadow:0 8px 28px rgba(0,0,0,.14);pointer-events:all;
    animation:toastIn .35s cubic-bezier(.4,0,.2,1) both}
.toast-item.out{animation:toastOut .3s ease forwards}
.toast-s{background:#e1f5ee;color:#085041;border-left:4px solid #0f6e56}
.toast-e{background:#fcebeb;color:#791f1f;border-left:4px solid #e24b4a}
.toast-i{background:#e6f1fb;color:#0c447c;border-left:4px solid #185fa5}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
@keyframes toastOut{to{opacity:0;transform:translateX(20px)}}

/* ── Skeleton ── */
.skel{background:linear-gradient(90deg,#f0efe9 25%,#e8e6e0 50%,#f0efe9 75%);
    background-size:200% 100%;animation:shim 1.4s infinite;border-radius:6px}
@keyframes shim{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ── Fade in ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.fade-up{animation:fadeUp .4s ease both}

/* ── Cards cours ── */
.c-card{border-radius:var(--card-r);border:1px solid #e8e6e0;overflow:hidden;
    transition:transform .22s,box-shadow .22s;background:#fff;
    text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}
.c-card:hover{transform:translateY(-5px) scale(1.01);
    box-shadow:0 16px 40px rgba(0,0,0,.11);color:inherit}
.c-img{position:relative;overflow:hidden;height:160px;background:#eeedfe;flex-shrink:0}
.c-img img{width:100%;height:100%;object-fit:cover;transition:transform .35s}
.c-card:hover .c-img img{transform:scale(1.06)}
.c-ov{position:absolute;inset:0;background:linear-gradient(to top,rgba(26,26,46,.45),transparent 55%);
    opacity:0;transition:opacity .25s}
.c-card:hover .c-ov{opacity:1}
.c-no-img{width:100%;height:100%;display:flex;align-items:center;
    justify-content:center;font-size:2.2rem;color:var(--brand)}
.c-cat{position:absolute;top:9px;left:9px;background:var(--brand);color:#fff;
    font-size:.67rem;font-weight:600;padding:.22rem .6rem;border-radius:5px;z-index:2}
.c-badge-new{position:absolute;top:9px;right:9px;background:#198754;color:#fff;
    font-size:.65rem;font-weight:700;padding:.2rem .55rem;border-radius:5px;z-index:2}
.c-badge-hot{position:absolute;top:9px;right:9px;background:#dc3545;color:#fff;
    font-size:.65rem;font-weight:700;padding:.2rem .55rem;border-radius:5px;z-index:2}
.c-body{padding:13px;display:flex;flex-direction:column;flex:1}
.c-title{font-size:.87rem;font-weight:600;color:#1a1a2e;line-height:1.35;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
    overflow:hidden;margin-bottom:4px;min-height:2.35em}
.c-teacher{font-size:.74rem;color:#888;margin-bottom:5px}
.c-stars{color:#f39c12;font-size:.76rem}
.c-meta{font-size:.71rem;color:#aaa}
.c-price{font-size:.98rem;font-weight:700;color:var(--brand);margin-top:auto;padding-top:5px}
.c-price.free{color:#198754}

/* ── Filter bar ── */
.f-btn{font-size:.79rem;padding:.32rem .95rem;border-radius:20px;
    border:1px solid #d3d1c7;background:#fff;color:#555;cursor:pointer;
    transition:all .15s;text-decoration:none;white-space:nowrap}
.f-btn:hover,.f-btn.on{background:var(--brand);color:#fff;border-color:var(--brand)}

/* ── Hero search ── */
#srch{border:1px solid #d3d1c7;border-radius:10px;padding:.55rem 1.1rem;
    font-size:.88rem;width:100%;outline:none;
    transition:border-color .15s,box-shadow .15s}
#srch:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(108,99,255,.12)}

/* ── Progress bar thin ── */
.prog-wrap{height:5px;border-radius:999px;background:#e8e6e0;overflow:hidden}
.prog-fill{height:100%;border-radius:999px;background:var(--brand);transition:width .5s}
.prog-fill.done{background:#198754}

/* ── Lesson sidebar ── */
.l-sidebar{background:#fff;border:1px solid #e8e6e0;border-radius:12px;
    overflow:hidden;position:sticky;top:calc(var(--nav-h) + 16px)}
.l-shd{background:var(--navy);padding:.85rem 1rem;color:#fff;
    font-size:.86rem;font-weight:600;display:flex;justify-content:space-between}
.l-item{display:flex;align-items:center;gap:9px;padding:.6rem 1rem;
    border-bottom:1px solid #f0efe9;font-size:.81rem;color:#444;
    transition:background .12s;text-decoration:none}
.l-item:hover{background:#f8f7f4;color:#1a1a2e}
.l-item.active-l{background:#eeedfe;color:var(--brand);font-weight:600}
.l-item.locked-l{color:#ccc;cursor:default;pointer-events:none}

/* ── Pagination ── */
.pagination .page-link{border-radius:7px!important;margin:0 2px;
    color:var(--brand);border-color:#e8e6e0}
.pagination .active .page-link{background:var(--brand);border-color:var(--brand)}

/* ── Review card ── */
.rv-card{padding:.9rem;border-radius:10px;border:1px solid #e8e6e0;
    background:#fff;margin-bottom:.7rem}
.rv-av{width:33px;height:33px;border-radius:50%;background:#eeedfe;
    color:#534ab7;font-size:.73rem;font-weight:700;
    display:flex;align-items:center;justify-content:center;flex-shrink:0}

/* ── Stat cards dashboard ── */
.stat-card{background:#fff;border:1px solid #e8e6e0;border-radius:12px;
    padding:1rem;display:flex;align-items:center;gap:12px;
    transition:transform .2s,box-shadow .2s}
.stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.07)}
.stat-ic{width:48px;height:48px;border-radius:10px;display:flex;
    align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.stat-val{font-size:1.7rem;font-weight:700;color:#1a1a2e;line-height:1}
.stat-lbl{font-size:.74rem;color:#888;margin-top:2px}
.stat-sub{font-size:.71rem;color:#aaa}

/* ── Bouton principal ── */
.btn-main{display:block;width:100%;padding:.8rem;border:none;border-radius:9px;
    font-size:.93rem;font-weight:600;text-align:center;cursor:pointer;
    transition:transform .12s,opacity .12s;text-decoration:none}
.btn-main:hover{transform:scale(1.02);opacity:.93}
.btn-purple{background:var(--brand);color:#fff}
.btn-green{background:#198754;color:#fff}
.btn-gold{background:#faeeda;color:#633806}
</style>
</head>
<body>

<!-- Overlay -->
<div id="ov" onclick="closeMob()"></div>

<!-- Menu mobile -->
<div id="mob" role="dialog" aria-modal="true">
    <div class="mob-hd">
        <div class="mob-hd-top">
            <div class="mob-brand"><i class="bi bi-mortarboard-fill"></i>LearnHub</div>
            <button class="mob-x" onclick="closeMob()"><i class="bi bi-x-lg"></i></button>
        </div>
        @auth
        <div class="d-flex align-items-center gap-2">
            <div class="u-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            <div style="overflow:hidden">
                <div class="u-nm">{{ auth()->user()->name }}</div>
                <div class="u-em">{{ auth()->user()->email }}</div>
            </div>
        </div>
        @else
        <div class="d-flex align-items-center gap-2">
            <div class="u-av" style="background:#444"><i class="bi bi-person"></i></div>
            <div><div class="u-nm">Visiteur</div><div class="u-em">Non connecté</div></div>
        </div>
        @endauth
    </div>

    <div class="mob-body">
        <div class="mob-lbl">Navigation</div>
        <a href="{{ route('dashboard') }}"
           class="mi {{ request()->routeIs('dashboard') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-p"><i class="bi bi-speedometer2"></i></span>Dashboard
            @auth<span class="ms-auto badge" style="background:var(--brand);font-size:.65rem">
                {{ ucfirst(auth()->user()->role) }}</span>@endauth
        </a>
        <a href="{{ route('courses.index') }}"
           class="mi {{ request()->routeIs('courses.index') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-t"><i class="bi bi-collection-play"></i></span>Catalogue
        </a>
        @auth
        @if(auth()->user()->isEtudiant())
        <a href="{{ route('dashboard.student') }}"
           class="mi {{ request()->routeIs('dashboard.student') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-g"><i class="bi bi-journals"></i></span>Mes cours
        </a>
        @endif
        @if(in_array(auth()->user()->role,['admin','formateur']))
        <div class="mob-lbl">Formateur</div>
        <a href="{{ route('dashboard.formateur') }}"
           class="mi {{ request()->routeIs('dashboard.formateur') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-a"><i class="bi bi-bar-chart-line"></i></span>Mes stats
        </a>
        <a href="{{ route('courses.create') }}"
           class="mi {{ request()->routeIs('courses.create') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-p"><i class="bi bi-plus-circle"></i></span>Créer un cours
        </a>
        @endif
        @if(auth()->user()->isAdmin())
        <div class="mob-lbl">Admin</div>
        <a href="{{ route('dashboard.admin') }}"
           class="mi {{ request()->routeIs('dashboard.admin') ? 'on' : '' }}" onclick="closeMob()">
            <span class="mi-ic ic-r"><i class="bi bi-shield-check"></i></span>Administration
        </a>
        @endif
        @endauth
    </div>

    <div class="mob-ft">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-lo">
                <span class="mi-ic ic-r"><i class="bi bi-box-arrow-right"></i></span>
                Déconnexion
            </button>
        </form>
        @else
        <a href="{{ route('login') }}" class="mi" onclick="closeMob()">
            <span class="mi-ic ic-p"><i class="bi bi-person-circle"></i></span>Se connecter
        </a>
        @endauth
    </div>
</div>

<!-- Navbar -->
<nav class="lms-nav">
    <div class="d-flex align-items-center gap-3">
        <button class="burger" id="brg" onclick="openMob()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('dashboard') }}" class="lms-brand">
            <i class="bi bi-mortarboard-fill"></i>LearnHub
        </a>
    </div>
    <div class="lms-links">
        <a href="{{ route('dashboard') }}"
           class="lms-link {{ request()->routeIs('dashboard') ? 'on' : '' }}">
            <i class="bi bi-grid me-1"></i>Dashboard
        </a>
        <a href="{{ route('courses.index') }}"
           class="lms-link {{ request()->routeIs('courses.*') ? 'on' : '' }}">
            <i class="bi bi-collection-play me-1"></i>Catalogue
        </a>
        @auth
        @if(in_array(auth()->user()->role,['admin','formateur']))
        <a href="{{ route('courses.create') }}" class="lms-link lms-create">
            <i class="bi bi-plus-lg me-1"></i>Nouveau cours
        </a>
        @endif
        <div class="dropdown ms-1">
            <button class="lms-link d-flex align-items-center gap-2"
                    style="background:transparent;border:none;cursor:pointer"
                    data-bs-toggle="dropdown">
                <div style="width:30px;height:30px;border-radius:50%;background:var(--brand);
                            color:#fff;font-size:.7rem;font-weight:700;
                            display:flex;align-items:center;justify-content:center">
                    {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                </div>
                <span style="font-size:.84rem">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down" style="font-size:.6rem"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                style="border:0.5px solid #e8e6e0;border-radius:10px;
                       font-size:.84rem;margin-top:6px">
                <li><span class="dropdown-item-text text-muted" style="font-size:.74rem">
                    {{ ucfirst(auth()->user()->role) }}</span></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="lms-link">Connexion</a>
        @endauth
    </div>
</nav>

<!-- Toast container -->
<div id="toast-wrap"></div>

<main>
    @yield('content')
    {{ $slot ?? '' }}
</main>

<!-- Flash → Toast automatique -->
@foreach(['success'=>'s','error'=>'e','info'=>'i'] as $type=>$cls)
    @if(session($type))
    <script>
    document.addEventListener('DOMContentLoaded',()=>
        showToast('{{ addslashes(session($type)) }}','{{ $cls }}'));
    </script>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── Mobile menu ── */
const mob=document.getElementById('mob'),ov=document.getElementById('ov'),
      brg=document.getElementById('brg');
let isOpen=false,tx=0;
function openMob(){isOpen=true;mob.classList.add('on');ov.classList.add('on');
    brg.classList.add('on');document.body.style.overflow='hidden'}
function closeMob(){isOpen=false;mob.classList.remove('on');ov.classList.remove('on');
    brg.classList.remove('on');document.body.style.overflow=''}
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&isOpen)closeMob()});
mob.addEventListener('touchstart',e=>{tx=e.touches[0].clientX},{passive:true});
mob.addEventListener('touchmove',e=>{
    const d=tx-e.touches[0].clientX;
    if(d>10){const m=Math.min(d,290);mob.style.transform=`translateX(-${m}px)`;
        ov.style.opacity=1-(m/290)*.8}},{passive:true});
mob.addEventListener('touchend',e=>{
    mob.style.transform='';ov.style.opacity='';
    if(tx-e.changedTouches[0].clientX>80)closeMob()});

/* ── Toasts ── */
function showToast(msg,type='s'){
    const w=document.getElementById('toast-wrap');
    const t=document.createElement('div');
    const icons={s:'bi-check-circle-fill',e:'bi-x-circle-fill',i:'bi-info-circle-fill'};
    t.className=`toast-item toast-${type}`;
    t.innerHTML=`<i class="bi ${icons[type]||icons.s}"></i><span style="flex:1">${msg}</span>
        <button onclick="this.parentElement.remove()"
            style="background:transparent;border:none;cursor:pointer;font-size:.9rem;
                   color:inherit;opacity:.6;padding:0 2px">&times;</button>`;
    w.appendChild(t);
    setTimeout(()=>{t.classList.add('out');setTimeout(()=>t.remove(),300)},4200);
}

/* ── Count-up ── */
function countUp(el,target,dur=1200){
    const start=performance.now();
    const fmt=v=>v>=1000?v.toLocaleString('fr'):Math.round(v);
    requestAnimationFrame(function tick(now){
        const p=Math.min((now-start)/dur,1);
        const ease=1-Math.pow(1-p,3);
        el.textContent=fmt(ease*target);
        if(p<1)requestAnimationFrame(tick);
    });
}
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('[data-count]').forEach(el=>{
        const io=new IntersectionObserver(en=>{
            if(en[0].isIntersecting){countUp(el,+el.dataset.count);io.disconnect()}
        },{threshold:.3});
        io.observe(el);
    });
});
</script>
</body>
</html>