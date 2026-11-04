<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LearnHub — Apprenez les compétences du futur</title>
<meta name="description" content="Plateforme d'apprentissage en ligne avec 500+ cours créés par des experts.">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* ═══════════════════════════════
   RESET & BASE
═══════════════════════════════ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;overflow-x:hidden}
body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#fff;color:#1a1a2e;overflow-x:hidden;line-height:1.6;-webkit-text-size-adjust:100%}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
ul{list-style:none}

/* ═══════════════════════════════
   TOKENS
═══════════════════════════════ */
:root{
    --p:#7c3aed;--p-dk:#5b21b6;--p-lt:#8b5cf6;
    --p-gl:rgba(124,58,237,.12);--acc:#06b6d4;
    --ok:#10b981;
    --t1:#0f0823;--t2:#374151;--t3:#6b7280;--t4:#9ca3af;
    --bg:#f9f7ff;--border:#ede9fe;
    --r:14px;--r-sm:10px;
    --sh:0 4px 24px rgba(124,58,237,.1),0 1px 4px rgba(0,0,0,.05);
    --sh-lg:0 20px 60px rgba(124,58,237,.14),0 4px 16px rgba(0,0,0,.06);
    --tr:all .22s cubic-bezier(.4,0,.2,1);
    --nav-h:62px;
}

/* ═══════════════════════════════
   UTILITAIRES
═══════════════════════════════ */
.container{max-width:1160px;margin:0 auto;padding:0 20px;width:100%}
.section{padding:88px 0}
.grad-text{background:linear-gradient(135deg,var(--p),var(--acc));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.tag{display:inline-flex;align-items:center;gap:6px;background:var(--p-gl);color:var(--p);border:1px solid rgba(124,58,237,.2);border-radius:999px;padding:.28rem .85rem;font-size:.7rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:.9rem}
.section-title{font-size:clamp(1.65rem,3.2vw,2.5rem);font-weight:800;color:var(--t1);line-height:1.15;letter-spacing:-.4px;margin-bottom:.7rem}
.section-sub{font-size:.96rem;color:var(--t3);line-height:1.8;max-width:540px}
.tc{text-align:center}.tc .section-sub{margin:0 auto}

.btn{display:inline-flex;align-items:center;gap:7px;border-radius:var(--r-sm);font-weight:600;cursor:pointer;border:none;transition:var(--tr);font-family:inherit;font-size:.9rem;text-decoration:none;white-space:nowrap;line-height:1}
.btn-p{background:linear-gradient(135deg,var(--p),var(--p-lt));color:#fff;padding:.78rem 1.5rem;box-shadow:0 4px 16px rgba(124,58,237,.35)}
.btn-p:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(124,58,237,.45);color:#fff}
.btn-o{background:transparent;color:var(--t1);border:1.5px solid var(--border);padding:.75rem 1.4rem}
.btn-o:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}
.btn-lg{padding:.9rem 1.85rem;font-size:.97rem}
.btn-gw{background:rgba(255,255,255,.14);color:#fff;border:1.5px solid rgba(255,255,255,.3);padding:.85rem 1.85rem;font-size:.97rem}
.btn-gw:hover{background:rgba(255,255,255,.22)}

/* ═══════════════════════════════
   NAVBAR — auto-hide premium
═══════════════════════════════ */
.navbar{
    position:fixed;top:0;left:0;right:0;z-index:1000;
    padding:0;
    /* Transform contrôle le hide/show */
    transform:translateY(0);
    transition:transform .38s cubic-bezier(.4,0,.2,1),
               background .3s ease,
               box-shadow .3s ease;
    will-change:transform;
}
/* Glassmorphism au scroll */
.navbar.sc{
    background:rgba(255,255,255,.88);
    backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
    box-shadow:0 1px 0 rgba(0,0,0,.07),0 4px 24px rgba(0,0,0,.06);
}
/* Cache la navbar en descendant */
.navbar.hidden{
    transform:translateY(-100%);
}
.nav-in{max-width:1160px;margin:0 auto;padding:0 20px;display:flex;align-items:center;height:var(--nav-h);gap:1.5rem}
.nav-logo{display:flex;align-items:center;gap:9px;flex-shrink:0;text-decoration:none}
.nav-logo-ic{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--p),var(--acc));display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 12px rgba(124,58,237,.38);transition:var(--tr)}
.nav-logo:hover .nav-logo-ic{transform:rotate(-5deg) scale(1.08)}
.nav-logo-n{font-size:1.1rem;font-weight:800;color:var(--t1)}
/* Sur fond sombre (hero non scrollé) : texte blanc */
.navbar:not(.sc) .nav-logo-n{color:#fff}
.navbar:not(.sc) .nav-link{color:rgba(255,255,255,.75)}
.navbar:not(.sc) .nav-link:hover{background:rgba(255,255,255,.12);color:#fff}
.navbar:not(.sc) .nav-btn-l{color:rgba(255,255,255,.75)}
.navbar:not(.sc) .nav-btn-l:hover{color:#fff;background:rgba(255,255,255,.12)}
.navbar:not(.sc) .burger span{background:#fff}

.nav-links{display:flex;align-items:center;gap:.15rem;flex:1}
.nav-link{padding:.4rem .78rem;border-radius:7px;font-size:.86rem;font-weight:500;color:var(--t2);transition:var(--tr);text-decoration:none}
.nav-link:hover{background:var(--p-gl);color:var(--p)}
.nav-acts{display:flex;align-items:center;gap:.5rem;margin-left:auto}
.nav-btn-l{font-size:.86rem;font-weight:600;color:var(--t2);padding:.4rem .82rem;border-radius:7px;transition:var(--tr);text-decoration:none}
.nav-btn-l:hover{color:var(--p);background:var(--p-gl)}
.nav-btn-r{background:var(--p);color:#fff;padding:.44rem 1rem;border-radius:7px;font-size:.86rem;font-weight:600;transition:var(--tr);text-decoration:none;display:flex;align-items:center;gap:5px}
.nav-btn-r:hover{background:var(--p-dk);transform:translateY(-1px)}

/* Burger */
.burger{display:none;flex-direction:column;gap:4px;background:none;border:none;cursor:pointer;padding:7px;border-radius:8px;transition:var(--tr);flex-shrink:0;position:relative;z-index:1001}
.burger:hover{background:rgba(255,255,255,.12)}
.navbar.sc .burger:hover{background:var(--p-gl)}
.burger span{display:block;width:20px;height:2px;background:var(--t1);border-radius:2px;transition:var(--tr);transform-origin:center}
.burger.on span:nth-child(1){transform:translateY(6px) rotate(45deg)}
.burger.on span:nth-child(2){opacity:0;transform:scaleX(0)}
.burger.on span:nth-child(3){transform:translateY(-6px) rotate(-45deg)}

/* Overlay mobile */
.mob-ov{
    position:fixed;inset:0;background:rgba(0,0,0,.58);
    z-index:1002;opacity:0;pointer-events:none;
    transition:opacity .28s ease;
    backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);
}
.mob-ov.on{opacity:1;pointer-events:all}

/* Drawer mobile */
.mob-drawer{
    position:fixed;top:0;right:0;bottom:0;
    width:min(288px,85vw);
    background:#fff;z-index:1003;
    transform:translateX(100%);
    transition:transform .3s cubic-bezier(.4,0,.2,1);
    box-shadow:-16px 0 50px rgba(0,0,0,.18);
    display:flex;flex-direction:column;overflow:hidden;
}
.mob-drawer.on{transform:translateX(0)}
.mob-hd{display:flex;justify-content:space-between;align-items:center;padding:.85rem 1.1rem;border-bottom:1px solid var(--border);flex-shrink:0}
.mob-close{
    width:32px;height:32px;border-radius:8px;
    background:var(--bg);border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    color:var(--t2);font-size:.95rem;transition:var(--tr);
}
.mob-close:hover{background:var(--p-gl);color:var(--p)}
.mob-body{flex:1;overflow-y:auto;padding:.65rem .9rem;overscroll-behavior:contain}
.mob-link{display:flex;align-items:center;gap:9px;padding:.65rem .9rem;border-radius:9px;font-size:.88rem;font-weight:500;color:var(--t2);transition:var(--tr);text-decoration:none}
.mob-link:hover{background:var(--p-gl);color:var(--p)}
.mob-link i{font-size:.95rem;color:var(--p);width:18px;text-align:center;flex-shrink:0}
.mob-div{height:1px;background:var(--border);margin:.55rem 0}
.mob-ft{padding:.9rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.45rem;flex-shrink:0}

/* ═══════════════════════════════
   HERO — compact, tient dans 100vh
═══════════════════════════════ */
.hero{
    /* Tient dans l'écran sans déborder */
    height:100vh;
    min-height:600px;
    max-height:900px;         /* plafond sur très grands écrans */
    background:linear-gradient(160deg,#0f0823 0%,#180a40 45%,#071830 100%);
    display:flex;align-items:center;
    /* top padding = hauteur navbar */
    padding:var(--nav-h) 0 0;
    position:relative;overflow:hidden;
}
.hero-orb{position:absolute;border-radius:50%;filter:blur(72px);pointer-events:none;animation:orbd 14s ease-in-out infinite}
.hero-orb.o1{width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.34) 0%,transparent 70%);top:-120px;left:-100px}
.hero-orb.o2{width:320px;height:320px;background:radial-gradient(circle,rgba(6,182,212,.22) 0%,transparent 70%);bottom:-60px;right:8%;animation-delay:-7s}
.hero-orb.o3{width:220px;height:220px;background:radial-gradient(circle,rgba(139,92,246,.28) 0%,transparent 70%);top:45%;left:38%;animation-delay:-3.5s}
@keyframes orbd{0%,100%{transform:scale(1) translate(0,0)}50%{transform:scale(1.07) translate(-12px,-16px)}}
.hero-grid{position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:30px 30px;pointer-events:none}

.hero-in{
    position:relative;z-index:1;
    max-width:1160px;margin:0 auto;padding:0 20px;
    display:grid;grid-template-columns:1fr 1fr;
    gap:2.5rem;align-items:center;width:100%;
    /* Empêche le hero-in de déborder verticalement */
    height:100%;
}

/* Badge */
.hero-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(124,58,237,.2);color:#c4b5fd;border:1px solid rgba(124,58,237,.32);border-radius:999px;padding:.28rem .9rem;font-size:.68rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:.85rem}
.hero-badge-dot{width:6px;height:6px;border-radius:50%;background:var(--acc);animation:blink 2s ease-in-out infinite;flex-shrink:0}
@keyframes blink{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.7)}}

.hero-h1{font-size:clamp(1.75rem,3.4vw,3rem);font-weight:800;color:#fff;line-height:1.1;letter-spacing:-.5px;margin-bottom:.9rem}
.hero-desc{font-size:.92rem;color:rgba(255,255,255,.5);line-height:1.78;max-width:420px;margin-bottom:1.65rem}
.hero-acts{display:flex;gap:.7rem;flex-wrap:wrap;margin-bottom:1.75rem}

.hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:.7rem}
.hero-stat{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:.7rem .55rem;text-align:center;transition:var(--tr)}
.hero-stat:hover{background:rgba(255,255,255,.1)}
.hero-stat-v{font-size:1.4rem;font-weight:800;color:#fff;line-height:1}
.hero-stat-l{font-size:.64rem;color:rgba(255,255,255,.42);margin-top:2px}

/* Mockup */
.hero-visual{position:relative;display:flex;align-items:center;justify-content:center;animation:floatY 7s ease-in-out infinite}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.mockup{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:18px;padding:1.15rem;backdrop-filter:blur(12px);width:100%;box-shadow:0 32px 64px rgba(0,0,0,.4)}
.mk-hd{display:flex;align-items:center;gap:.5rem;margin-bottom:.9rem}
.mk-dots{display:flex;gap:.3rem}
.mk-dot{width:8px;height:8px;border-radius:50%}
.mk-bar{flex:1;height:8px;border-radius:5px;background:rgba(255,255,255,.1)}
.mk-course{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:.75rem;margin-bottom:.52rem;display:flex;align-items:center;gap:.72rem;transition:var(--tr)}
.mk-course:hover{background:rgba(255,255,255,.11)}
.mk-emoji{width:38px;height:38px;border-radius:7px;background:rgba(255,255,255,.07);display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0}
.mk-name{font-size:.76rem;font-weight:600;color:#fff;margin-bottom:.14rem}
.mk-meta{font-size:.65rem;color:rgba(255,255,255,.42)}
.mk-prog{height:3px;border-radius:999px;background:rgba(255,255,255,.1);margin-top:.38rem;overflow:hidden}
.mk-fill{height:100%;border-radius:999px}
.mk-pct{font-size:.64rem;color:rgba(255,255,255,.4);flex-shrink:0}

.float-badge{position:absolute;background:#fff;border-radius:12px;padding:.55rem .85rem;box-shadow:0 10px 32px rgba(0,0,0,.2);display:flex;align-items:center;gap:.62rem;font-size:.74rem;animation:floatY 5s ease-in-out infinite}
.fb1{bottom:-14px;left:-16px;animation-delay:-.5s}
.fb2{top:-12px;right:-14px;animation-delay:-2.5s}
.fb-icon{font-size:1.05rem}
.fb-title{font-weight:700;color:var(--t1)}
.fb-sub{font-size:.64rem;color:var(--t3)}

/* ═══════════════════════════════
   TRUST BAR
═══════════════════════════════ */
.trust{background:var(--bg);padding:2rem 20px;border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.trust-in{max-width:1160px;margin:0 auto;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;justify-content:center}
.trust-lbl{font-size:.74rem;color:var(--t4);font-weight:500;white-space:nowrap;flex-shrink:0}
.trust-logos{display:flex;align-items:center;gap:1.6rem;flex-wrap:wrap;justify-content:center}
.trust-logo{font-size:.86rem;font-weight:700;color:var(--t4);opacity:.55;transition:var(--tr)}
.trust-logo:hover{opacity:1;color:var(--p)}

/* ═══════════════════════════════
   CATÉGORIES
═══════════════════════════════ */
.cat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.1rem;margin-top:2.75rem}
.cat-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--r);padding:1.5rem 1.25rem;transition:var(--tr);cursor:pointer;display:flex;align-items:flex-start;gap:.85rem;position:relative;overflow:hidden}
.cat-card::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--p-gl),transparent);opacity:0;transition:opacity .25s}
.cat-card:hover{border-color:var(--p);transform:translateY(-4px);box-shadow:var(--sh-lg)}
.cat-card:hover::after{opacity:1}
.cat-ic{width:48px;height:48px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;position:relative;z-index:1}
.cat-info{flex:1;min-width:0;position:relative;z-index:1}
.cat-name{font-size:.92rem;font-weight:700;color:var(--t1);margin-bottom:.22rem}
.cat-cnt{font-size:.74rem;color:var(--t4)}
.cat-arr{margin-left:auto;font-size:.86rem;color:var(--t4);transition:var(--tr);align-self:center;flex-shrink:0;position:relative;z-index:1}
.cat-card:hover .cat-arr{color:var(--p);transform:translateX(4px)}

/* ═══════════════════════════════
   CARROUSEL
═══════════════════════════════ */
.carousel-wrap{margin-top:2.75rem;position:relative}
.carousel-track-outer{overflow:hidden;border-radius:var(--r)}
.carousel-track{display:flex;gap:1.25rem;transition:transform .46s cubic-bezier(.4,0,.2,1);will-change:transform}
.c-card{flex:0 0 calc(33.333% - 0.84rem);background:#fff;border-radius:var(--r);border:1px solid var(--border);overflow:hidden;transition:var(--tr);display:flex;flex-direction:column}
.c-card:hover{transform:translateY(-5px);box-shadow:var(--sh-lg);border-color:rgba(124,58,237,.2)}
.c-thumb{height:162px;display:flex;align-items:center;justify-content:center;font-size:2.6rem;position:relative;overflow:hidden}
.c-badge{position:absolute;top:10px;left:10px;background:var(--p);color:#fff;font-size:.62rem;font-weight:700;padding:.17rem .5rem;border-radius:5px}
.c-body{padding:1.1rem;flex:1;display:flex;flex-direction:column}
.c-cat{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--p);margin-bottom:.42rem}
.c-title{font-size:.9rem;font-weight:700;color:var(--t1);line-height:1.4;margin-bottom:.48rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.c-auth{font-size:.74rem;color:var(--t3);margin-bottom:.6rem}
.c-stars{display:flex;align-items:center;gap:4px;margin-bottom:.6rem;font-size:.76rem}
.c-stars span{color:#f59e0b}
.c-rat{font-weight:700;color:var(--t1)}.c-rev{color:var(--t4)}
.c-ft{display:flex;align-items:center;justify-content:space-between;padding-top:.75rem;border-top:1px solid var(--border);margin-top:auto}
.c-price{font-size:.98rem;font-weight:800;color:var(--p)}
.c-stu{font-size:.72rem;color:var(--t4);display:flex;align-items:center;gap:3px}
.car-nav{display:flex;align-items:center;justify-content:center;gap:1rem;margin-top:1.75rem}
.car-btn{width:40px;height:40px;border-radius:50%;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.95rem;color:var(--t2);transition:var(--tr)}
.car-btn:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}
.car-dots{display:flex;gap:.5rem;align-items:center}
.car-dot{width:7px;height:7px;border-radius:50%;background:var(--border);cursor:pointer;transition:var(--tr)}
.car-dot.on{background:var(--p);width:20px;border-radius:999px}

/* ═══════════════════════════════
   POURQUOI
═══════════════════════════════ */
.why-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-top:3rem;align-items:center}
.why-list{display:flex;flex-direction:column;gap:1.25rem}
.why-item{display:flex;gap:1.1rem;align-items:flex-start;padding:1.2rem;border-radius:var(--r);border:1.5px solid var(--border);background:#fff;transition:var(--tr);cursor:default}
.why-item:hover{border-color:var(--p);box-shadow:var(--sh);transform:translateX(5px)}
.why-ic{width:46px;height:46px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.why-title{font-size:.91rem;font-weight:700;color:var(--t1);margin-bottom:.28rem}
.why-desc{font-size:.81rem;color:var(--t3);line-height:1.65}
.why-vis{background:linear-gradient(145deg,#0f0823,#1a0a3e);border-radius:16px;padding:2rem;display:flex;flex-direction:column;gap:.85rem;position:relative;overflow:hidden;min-height:390px;justify-content:center}
.why-vis::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:22px 22px}
.wsc{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:11px;padding:.95rem 1.1rem;position:relative;z-index:1;transition:var(--tr)}
.wsc:hover{background:rgba(255,255,255,.12);transform:scale(1.02)}
.wsc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.28rem}
.wsc-lbl{font-size:.72rem;color:rgba(255,255,255,.48);font-weight:500}
.wsc-val{font-size:1.3rem;font-weight:800;color:#fff}
.wsc-bar{height:4px;border-radius:999px;background:rgba(255,255,255,.1);overflow:hidden}
.wsc-fill{height:100%;border-radius:999px}

/* ═══════════════════════════════
   STATS
═══════════════════════════════ */
.stats-s{background:linear-gradient(135deg,var(--p),var(--p-lt));padding:68px 20px;position:relative;overflow:hidden}
.stats-s::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.07) 1px,transparent 1px);background-size:26px 26px}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;position:relative;z-index:1;max-width:1160px;margin:0 auto}
.stat-box{text-align:center;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:13px;padding:1.6rem 1rem;transition:var(--tr)}
.stat-box:hover{background:rgba(255,255,255,.14);transform:translateY(-4px)}
.stat-ic{font-size:1.7rem;margin-bottom:.55rem}
.stat-num{font-size:clamp(1.8rem,3.2vw,2.6rem);font-weight:800;color:#fff;line-height:1}
.stat-lbl{font-size:.78rem;color:rgba(255,255,255,.62);margin-top:.32rem;font-weight:500}

/* ═══════════════════════════════
   TÉMOIGNAGES
═══════════════════════════════ */
.testi-s{background:var(--bg)}
.testi-wrap{margin-top:2.75rem;position:relative;overflow:hidden}
.testi-track{display:flex;gap:1.25rem;transition:transform .46s cubic-bezier(.4,0,.2,1)}
.testi-card{flex:0 0 calc(33.333% - 0.84rem);background:#fff;border:1.5px solid var(--border);border-radius:var(--r);padding:1.55rem;transition:var(--tr);position:relative;display:flex;flex-direction:column}
.testi-card:hover{border-color:var(--p);box-shadow:var(--sh-lg);transform:translateY(-4px)}
.testi-card::before{content:'"';position:absolute;top:.65rem;right:1rem;font-size:3.2rem;color:var(--p-gl);font-family:Georgia,serif;line-height:1;font-weight:700}
.t-stars{color:#f59e0b;font-size:.86rem;margin-bottom:.85rem;letter-spacing:1px}
.t-txt{font-size:.85rem;color:var(--t2);line-height:1.78;margin-bottom:1rem;flex:1}
.t-auth{display:flex;align-items:center;gap:.75rem}
.t-av{width:38px;height:38px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,var(--p),var(--acc));display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#fff}
.t-name{font-size:.84rem;font-weight:700;color:var(--t1)}
.t-role{font-size:.72rem;color:var(--t3)}
.testi-nav{display:flex;align-items:center;justify-content:center;gap:1rem;margin-top:1.75rem}

/* ═══════════════════════════════
   CTA FINAL — visuellement distinct
   Fond blanc + card premium
═══════════════════════════════ */
.cta-s{
    background:#fff;
    padding:96px 20px;
    position:relative;overflow:hidden;
}
/* Bande de couleur subtile en haut */
.cta-s::before{
    content:'';position:absolute;top:0;left:0;right:0;height:3px;
    background:linear-gradient(90deg,transparent 5%,var(--p) 35%,var(--acc) 65%,transparent 95%);
}
/* Gradient de fond très subtil */
.cta-s::after{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(124,58,237,.06) 0%,transparent 70%);
    pointer-events:none;
}
.cta-in{
    position:relative;z-index:1;
    max-width:740px;margin:0 auto;
    background:#fff;
    border:1.5px solid var(--border);
    border-radius:22px;
    padding:3.25rem 3rem 3rem;
    box-shadow:0 2px 0 rgba(124,58,237,.1),var(--sh-lg);
    text-align:center;
    /* Accent bar interne en haut */
    overflow:hidden;
}
.cta-in::before{
    content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
    width:72px;height:3px;border-radius:0 0 4px 4px;
    background:linear-gradient(90deg,var(--p),var(--acc));
}
.cta-tag{
    display:inline-flex;align-items:center;gap:6px;
    background:var(--p-gl);color:var(--p);
    border:1px solid rgba(124,58,237,.2);
    border-radius:999px;padding:.28rem .85rem;
    font-size:.7rem;font-weight:700;letter-spacing:1.5px;
    text-transform:uppercase;margin-bottom:1.25rem;
}
.cta-title{
    font-size:clamp(1.55rem,3vw,2.3rem);
    font-weight:800;color:var(--t1);
    margin-bottom:.85rem;line-height:1.15;letter-spacing:-.4px;
}
.cta-sub{
    font-size:.93rem;color:var(--t3);
    line-height:1.8;margin-bottom:2.1rem;
    max-width:460px;margin-left:auto;margin-right:auto;
}
.cta-acts{display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap;margin-bottom:1.6rem}
.btn-cta-p{
    background:linear-gradient(135deg,var(--p),var(--p-lt));
    color:#fff;padding:.88rem 1.85rem;font-size:.97rem;
    box-shadow:0 4px 16px rgba(124,58,237,.35);
}
.btn-cta-p:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(124,58,237,.45);color:#fff}
.btn-cta-o{
    background:transparent;color:var(--p);
    border:1.5px solid rgba(124,58,237,.28);
    padding:.86rem 1.85rem;font-size:.97rem;
}
.btn-cta-o:hover{background:var(--p-gl);border-color:var(--p)}
/* Trust chips */
.cta-trust{display:flex;align-items:center;justify-content:center;gap:1.35rem;flex-wrap:wrap}
.cta-trust-item{display:flex;align-items:center;gap:5px;font-size:.76rem;color:var(--t4);font-weight:500}
.cta-trust-item i{color:var(--ok);font-size:.8rem}

/* ═══════════════════════════════
   NEWSLETTER
═══════════════════════════════ */
.nl-s{background:var(--bg);padding:72px 20px;border-top:1px solid var(--border)}
.nl-box{max-width:540px;margin:0 auto;text-align:center}
.nl-form{display:flex;gap:.6rem;margin-top:1.5rem;max-width:420px;margin-left:auto;margin-right:auto}
.nl-input{flex:1;padding:.72rem 1.05rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font:inherit;font-size:.87rem;outline:none;color:var(--t1);transition:var(--tr);min-width:0;background:#fff}
.nl-input:focus{border-color:var(--p);box-shadow:0 0 0 3px rgba(124,58,237,.1)}
.nl-btn{background:var(--p);color:#fff;border:none;padding:.72rem 1.2rem;border-radius:var(--r-sm);font:600 .87rem/1 inherit;cursor:pointer;transition:var(--tr);white-space:nowrap;flex-shrink:0;display:flex;align-items:center;gap:5px}
.nl-btn:hover{background:var(--p-dk);transform:translateY(-1px)}
.nl-note{font-size:.71rem;color:var(--t4);margin-top:.7rem}

/* ═══════════════════════════════
   FOOTER
═══════════════════════════════ */
.footer{background:#080516;padding:56px 20px 26px;border-top:1px solid rgba(255,255,255,.06)}
.footer-grid{display:grid;grid-template-columns:2fr repeat(3,1fr);gap:2.25rem;max-width:1160px;margin:0 auto 2.25rem}
.f-logo{display:flex;align-items:center;gap:9px;text-decoration:none;margin-bottom:.85rem}
.f-desc{font-size:.8rem;color:rgba(255,255,255,.34);line-height:1.78;max-width:240px}
.f-socials{display:flex;gap:.5rem;margin-top:1.1rem}
.f-soc{width:32px;height:32px;border-radius:7px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.44);font-size:.82rem;transition:var(--tr);text-decoration:none}
.f-soc:hover{background:var(--p);border-color:var(--p);color:#fff;transform:translateY(-2px)}
.f-col-ttl{font-size:.76rem;font-weight:700;color:rgba(255,255,255,.78);text-transform:uppercase;letter-spacing:1px;margin-bottom:.9rem}
.f-links{display:flex;flex-direction:column;gap:.5rem}
.f-link{font-size:.8rem;color:rgba(255,255,255,.33);text-decoration:none;transition:color .18s}
.f-link:hover{color:rgba(255,255,255,.76)}
.f-bottom{max-width:1160px;margin:0 auto;padding-top:1.6rem;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem}
.f-copy{font-size:.76rem;color:rgba(255,255,255,.24)}
.f-dev{font-size:.76rem;color:rgba(255,255,255,.36);display:flex;align-items:center;gap:5px}
.f-dev strong{color:#c4b5fd;font-weight:700}
.f-legal{display:flex;gap:1.2rem}
.f-legal a{font-size:.74rem;color:rgba(255,255,255,.24);text-decoration:none;transition:color .18s}
.f-legal a:hover{color:rgba(255,255,255,.58)}

/* ═══════════════════════════════
   REVEAL
═══════════════════════════════ */
.rv{opacity:0;transform:translateY(24px);transition:opacity .58s ease,transform .58s ease}
.rv.in{opacity:1;transform:translateY(0)}

/* ═══════════════════════════════
   RESPONSIVE
═══════════════════════════════ */
@media(max-width:1024px){
    .hero-in{gap:2rem}
    .cat-grid{grid-template-columns:repeat(2,1fr)}
    .why-grid{grid-template-columns:1fr}
    .why-vis{min-height:240px}
    .stats-grid{grid-template-columns:repeat(2,1fr)}
    .footer-grid{grid-template-columns:1fr 1fr;gap:1.75rem}
    .c-card{flex:0 0 calc(50% - 0.63rem)}
    .testi-card{flex:0 0 calc(50% - 0.63rem)}
    .cta-in{padding:2.75rem 2.25rem}
}

@media(max-width:768px){
    :root{--nav-h:58px}
    .section{padding:60px 0}
    /* Hero mobile */
    .hero{height:auto;min-height:100svh;max-height:none;padding:calc(var(--nav-h) + 24px) 0 40px}
    .hero-in{grid-template-columns:1fr;gap:1.75rem;text-align:center;height:auto;align-items:flex-start;padding-top:1rem}
    .hero-desc{margin:0 auto 1.6rem}
    .hero-acts{justify-content:center}
    .hero-stats{grid-template-columns:repeat(3,1fr);gap:.55rem}
    .hero-visual{display:none}
    /* Cat 2 col */
    .cat-grid{grid-template-columns:repeat(2,1fr);gap:.8rem}
    .cat-card{padding:1.05rem .95rem;gap:.7rem}
    .cat-ic{width:42px;height:42px;font-size:1.1rem;border-radius:10px}
    /* Carrousel 1 */
    .c-card{flex:0 0 100%}
    .testi-card{flex:0 0 100%}
    /* Stats 2×2 */
    .stats-grid{grid-template-columns:repeat(2,1fr);gap:.9rem}
    /* Footer */
    .footer-grid{grid-template-columns:1fr 1fr;gap:1.5rem}
    /* Nav */
    .nav-links,.nav-acts{display:none}
    .burger{display:flex}
    /* Why */
    .why-grid{gap:1.25rem}
    /* CTA */
    .cta-in{padding:2.25rem 1.5rem;border-radius:18px}
    .cta-title{font-size:1.5rem}
    .cta-acts{gap:.6rem}
    /* Newsletter */
    .nl-form{flex-direction:column}
}

@media(max-width:480px){
    .hero-stats{gap:.42rem}
    .hero-stat{padding:.6rem .35rem}
    .hero-stat-v{font-size:1.2rem}
    .hero-stat-l{font-size:.6rem}
    .hero-acts{flex-direction:column;align-items:stretch}
    .hero-acts .btn{justify-content:center}
    .cat-grid{grid-template-columns:repeat(2,1fr);gap:.6rem}
    .cat-card{padding:.85rem .75rem;gap:.6rem}
    .cat-name{font-size:.84rem}
    .stats-grid{gap:.7rem}
    .stat-box{padding:1.15rem .7rem}
    .footer-grid{grid-template-columns:1fr 1fr}
    .f-bottom{flex-direction:column;align-items:flex-start;gap:.6rem}
    .cta-acts{flex-direction:column;align-items:stretch}
    .cta-acts .btn{justify-content:center;width:100%}
    .cta-trust{gap:.85rem}
}

@media(max-width:360px){
    .container{padding:0 14px}
    .nav-in{padding:0 14px}
    .hero-badge{font-size:.62rem;padding:.24rem .72rem}
    .hero-stats{grid-template-columns:repeat(3,1fr)}
    .cat-grid{gap:.5rem}
}
</style>
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar" id="nav">
    <div class="nav-in">
        <a href="/" class="nav-logo">
            <div class="nav-logo-ic"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
            <span class="nav-logo-n">LearnHub</span>
        </a>
        <div class="nav-links">
            <a href="/" class="nav-link">Accueil</a>
            <a href="{{ route('courses.index') }}" class="nav-link">Catalogue</a>
            <a href="#pourquoi" class="nav-link">Avantages</a>
            <a href="#contact" class="nav-link">Contact</a>
        </div>
        <div class="nav-acts">
            <a href="{{ route('login') }}" class="nav-btn-l">Connexion</a>
            <a href="{{ route('register') }}" class="nav-btn-r">
                <i class="bi bi-person-plus" style="font-size:.76rem"></i> S'inscrire
            </a>
        </div>
        <button class="burger" id="burger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Overlay --}}
<div class="mob-ov" id="mob-ov" aria-hidden="true"></div>

{{-- Drawer mobile --}}
<div class="mob-drawer" id="mob-drawer" aria-hidden="true">
    <div class="mob-hd">
        <a href="/" class="nav-logo">
            <div class="nav-logo-ic" style="width:30px;height:30px;font-size:.88rem">
                <i class="bi bi-mortarboard-fill" style="color:#fff"></i>
            </div>
            <span class="nav-logo-n">LearnHub</span>
        </a>
        <button class="mob-close" id="mob-close" aria-label="Fermer">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="mob-body">
        <a href="/" class="mob-link" data-mob-close>
            <i class="bi bi-house"></i> Accueil
        </a>
        <a href="{{ route('courses.index') }}" class="mob-link" data-mob-close>
            <i class="bi bi-collection-play"></i> Catalogue
        </a>
        <a href="#pourquoi" class="mob-link" data-mob-close>
            <i class="bi bi-stars"></i> Avantages
        </a>
        <a href="#stats" class="mob-link" data-mob-close>
            <i class="bi bi-bar-chart"></i> Statistiques
        </a>
        <a href="#contact" class="mob-link" data-mob-close>
            <i class="bi bi-chat-dots"></i> Contact
        </a>
        <div class="mob-div"></div>
        <a href="#newsletter" class="mob-link" data-mob-close>
            <i class="bi bi-envelope-check"></i> Newsletter
        </a>
    </div>
    <div class="mob-ft">
        <a href="{{ route('login') }}" class="btn btn-o" style="justify-content:center" data-mob-close>
            Connexion
        </a>
        <a href="{{ route('register') }}" class="btn btn-p" style="justify-content:center" data-mob-close>
            <i class="bi bi-person-plus"></i> Créer un compte
        </a>
    </div>
</div>

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-orb o1"></div>
    <div class="hero-orb o2"></div>
    <div class="hero-orb o3"></div>
    <div class="hero-in">
        <div>
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Plateforme #1 en Afrique francophone
            </div>
            <h1 class="hero-h1">
                Apprenez les<br>
                <span class="grad-text">compétences</span><br>
                du futur.
            </h1>
            <p class="hero-desc">
                500+ cours créés par des experts. Progressez à votre rythme,
                certifiez vos compétences et transformez votre carrière.
            </p>
            <div class="hero-acts">
                <a href="{{ route('register') }}" class="btn btn-p btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Commencer gratuitement
                </a>
                <a href="{{ route('courses.index') }}" class="btn btn-gw btn-lg">
                    <i class="bi bi-collection-play"></i> Voir les cours
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-v" data-count="20000">20k+</div>
                    <div class="hero-stat-l">Apprenants</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-v" data-count="500">500+</div>
                    <div class="hero-stat-l">Cours</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-v">4.9★</div>
                    <div class="hero-stat-l">Note moy.</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="mockup">
                <div class="mk-hd">
                    <div class="mk-dots">
                        <div class="mk-dot" style="background:#ef4444"></div>
                        <div class="mk-dot" style="background:#f59e0b"></div>
                        <div class="mk-dot" style="background:#10b981"></div>
                    </div>
                    <div class="mk-bar"></div>
                </div>
                @php
                $mkc = [
                    ['emoji'=>'🐍','name'=>'Python & Machine Learning','meta'=>'Idrissa Sow · 12 leçons','pct'=>78,'color'=>'#7c3aed'],
                    ['emoji'=>'⚡','name'=>'Laravel 12 Backend Pro','meta'=>'Aminata Fall · 18 leçons','pct'=>45,'color'=>'#06b6d4'],
                    ['emoji'=>'🎨','name'=>'UI/UX Design System','meta'=>'Moussa Diop · 8 leçons','pct'=>91,'color'=>'#10b981'],
                ];
                @endphp
                @foreach($mkc as $m)
                <div class="mk-course">
                    <div class="mk-emoji">{{ $m['emoji'] }}</div>
                    <div style="flex:1;min-width:0">
                        <div class="mk-name">{{ $m['name'] }}</div>
                        <div class="mk-meta">{{ $m['meta'] }}</div>
                        <div class="mk-prog">
                            <div class="mk-fill" style="width:{{ $m['pct'] }}%;background:{{ $m['color'] }}"></div>
                        </div>
                    </div>
                    <div class="mk-pct">{{ $m['pct'] }}%</div>
                </div>
                @endforeach
            </div>
            <div class="float-badge fb1">
                <div class="fb-icon">🏆</div>
                <div><div class="fb-title">Certificat obtenu</div><div class="fb-sub">Python Developer</div></div>
            </div>
            <div class="float-badge fb2">
                <div class="fb-icon">🎓</div>
                <div><div class="fb-title">+2 300 inscrits</div><div class="fb-sub">cette semaine</div></div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TRUST ══ --}}
<div class="trust">
    <div class="trust-in">
        <span class="trust-lbl">Recommandé par</span>
        <div class="trust-logos">
            @php $logos = ['Google','Microsoft','Amazon','Meta','Dakar Tech','Coursera','LinkedIn']; @endphp
            @foreach($logos as $lg)
            <span class="trust-logo">{{ $lg }}</span>
            @endforeach
        </div>
    </div>
</div>

{{-- ══ CATÉGORIES ══ --}}
<section class="section">
    <div class="container">
        <div class="tc rv">
            <div class="tag"><i class="bi bi-grid-3x3-gap"></i> Domaines</div>
            <h2 class="section-title">Explorez par <span class="grad-text">catégorie</span></h2>
            <p class="section-sub">Trouvez le domaine qui vous passionne parmi nos formations professionnelles.</p>
        </div>
        @php
        $cats = [
            ['bi-code-slash','Développement Web','120+ cours','#7c3aed','#eeedfe'],
            ['bi-phone','Mobile & Apps','68+ cours','#06b6d4','#e0f7fa'],
            ['bi-cpu','Intelligence Artificielle','45+ cours','#f59e0b','#fffbeb'],
            ['bi-shield-lock','Cybersécurité','38+ cours','#ef4444','#fff5f5'],
            ['bi-palette','Design & UX/UI','52+ cours','#10b981','#ecfdf5'],
            ['bi-megaphone','Marketing Digital','61+ cours','#8b5cf6','#f5f3ff'],
        ];
        @endphp
        <div class="cat-grid">
            @foreach($cats as $i => $c)
            <div class="cat-card rv" style="transition-delay:{{ $i * 65 }}ms">
                <div class="cat-ic" style="background:{{ $c[4] }};color:{{ $c[3] }}">
                    <i class="bi {{ $c[0] }}"></i>
                </div>
                <div class="cat-info">
                    <div class="cat-name">{{ $c[1] }}</div>
                    <div class="cat-cnt">{{ $c[2] }}</div>
                </div>
                <i class="bi bi-arrow-right cat-arr"></i>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ COURS POPULAIRES — CARROUSEL ══ --}}
<section class="section" style="background:var(--bg)">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap" class="rv">
            <div>
                <div class="tag"><i class="bi bi-fire"></i> Tendances</div>
                <h2 class="section-title">Cours <span class="grad-text">populaires</span></h2>
            </div>
            <a href="{{ route('courses.index') }}" class="btn btn-o">Tout voir <i class="bi bi-arrow-right"></i></a>
        </div>
        @php
        $courses = [
            ['🐍','Maîtriser Python & ML','Idrissa Sow','4.9','(1842)','8 320','39€','Bestseller','#fef3c7','IA & Data'],
            ['⚡','Laravel 12 Backend Avancé','Aminata Fall','4.8','(976)','4 150','49€','Nouveau','#ede9fe','Dev Web'],
            ['🎨','Design System & Figma','Moussa Diop','4.9','(2104)','11 580','34€','Populaire','#ecfdf5','Design'],
            ['📱','React Native iOS & Android','Fatou Ba','4.7','(654)','3 210','44€','Tendance','#fce7f3','Mobile'],
            ['🔐','Ethical Hacking Pro','Cheikh Ndiaye','4.8','(1230)','6 740','54€','Top noté','#fff7ed','Sécurité'],
            ['🤖','ChatGPT & IA pour pros','Rokhaya Sow','4.9','(3450)','18 200','29€','Bestseller','#f0f9ff','IA'],
        ];
        @endphp
        <div class="carousel-wrap rv">
            <div class="carousel-track-outer" id="car-outer">
                <div class="carousel-track" id="car-track">
                    @foreach($courses as $c)
                    <div class="c-card">
                        <div class="c-thumb" style="background:{{ $c[8] }}">
                            <span>{{ $c[0] }}</span>
                            <div class="c-badge">{{ $c[7] }}</div>
                        </div>
                        <div class="c-body">
                            <div class="c-cat">{{ $c[9] }}</div>
                            <div class="c-title">{{ $c[1] }}</div>
                            <div class="c-auth"><i class="bi bi-person"></i> {{ $c[2] }}</div>
                            <div class="c-stars">
                                <span>★★★★★</span>
                                <span class="c-rat">{{ $c[3] }}</span>
                                <span class="c-rev">{{ $c[4] }}</span>
                            </div>
                            <div class="c-ft">
                                <div class="c-price">{{ $c[6] }}</div>
                                <div class="c-stu"><i class="bi bi-people"></i> {{ $c[5] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="car-nav">
                <button class="car-btn" id="car-prev" aria-label="Précédent"><i class="bi bi-arrow-left"></i></button>
                <div class="car-dots" id="car-dots"></div>
                <button class="car-btn" id="car-next" aria-label="Suivant"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</section>

{{-- ══ POURQUOI ══ --}}
<section class="section" id="pourquoi">
    <div class="container">
        <div class="why-grid">
            <div class="rv">
                <div class="tag"><i class="bi bi-stars"></i> Avantages</div>
                <h2 class="section-title">Pourquoi choisir <span class="grad-text">LearnHub</span> ?</h2>
                <p class="section-sub" style="margin-bottom:1.75rem">Une plateforme pensée pour votre réussite avec des outils et un suivi de qualité.</p>
                @php
                $why = [
                    ['bi-award','Certificats reconnus',"Obtenez des certifications valorisées par les entreprises du secteur.",'#eeedfe','#7c3aed'],
                    ['bi-person-workspace','Formateurs experts',"Chaque cours est créé par des professionnels actifs dans leur domaine.",'#ecfdf5','#10b981'],
                    ['bi-infinity','Accès à vie',"Payez une fois, accédez à vie. Mises à jour incluses sans frais.",'#fffbeb','#f59e0b'],
                    ['bi-phone','Apprentissage flexible',"Apprenez quand vous voulez, depuis n'importe quel appareil.",'#f5f3ff','#8b5cf6'],
                ];
                @endphp
                <div class="why-list">
                    @foreach($why as $w)
                    <div class="why-item">
                        <div class="why-ic" style="background:{{ $w[3] }};color:{{ $w[4] }}">
                            <i class="bi {{ $w[0] }}"></i>
                        </div>
                        <div>
                            <div class="why-title">{{ $w[1] }}</div>
                            <div class="why-desc">{{ $w[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="why-vis rv" style="transition-delay:130ms">
                @php
                $wstats = [
                    ['Taux de réussite','92%',92,'#10b981'],
                    ['Satisfaction globale','98%',98,'#7c3aed'],
                    ['Cours complétés','87%',87,'#06b6d4'],
                    ['Recommandation','95%',95,'#f59e0b'],
                ];
                @endphp
                @foreach($wstats as $ws)
                <div class="wsc">
                    <div class="wsc-top">
                        <span class="wsc-lbl">{{ $ws[0] }}</span>
                        <span class="wsc-val">{{ $ws[1] }}</span>
                    </div>
                    <div class="wsc-bar">
                        <div class="wsc-fill" style="width:{{ $ws[2] }}%;background:{{ $ws[3] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ STATS ══ --}}
<section class="stats-s" id="stats">
    <div class="stats-grid">
        @php
        $stats = [
            ['🎓','20 000+','Étudiants formés',20000],
            ['📚','500+','Cours disponibles',500],
            ['👨‍🏫','85+','Formateurs experts',85],
            ['🏆','12 000+','Certifications',12000],
        ];
        @endphp
        @foreach($stats as $s)
        <div class="stat-box rv">
            <div class="stat-ic">{{ $s[0] }}</div>
            <div class="stat-num" data-count="{{ $s[3] }}">{{ $s[1] }}</div>
            <div class="stat-lbl">{{ $s[2] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ══ TÉMOIGNAGES ══ --}}
<section class="section testi-s">
    <div class="container">
        <div class="tc rv">
            <div class="tag"><i class="bi bi-chat-quote"></i> Témoignages</div>
            <h2 class="section-title">Ce que disent nos <span class="grad-text">apprenants</span></h2>
        </div>
        @php
        $testis = [
            ['AM','Amadou Mbaye','Dev Senior · Dakar',"LearnHub a totalement transformé ma carrière. En 4 mois, j'ai décroché un poste de dev senior grâce au cours Laravel. La qualité est exceptionnelle."],
            ['FN','Fatou Ndiaye','Designer UX · Abidjan',"Le cours Design System m'a permis de décrocher mon premier client freelance. La pédagogie est claire et progressive. Je recommande à 100%."],
            ['CD','Cheikh Diallo','Entrepreneur · Dakar',"J'ai lancé mon SaaS après le cours IA et Python. L'accompagnement et la communauté sont des atouts majeurs. Rentabilisé en 2 mois."],
            ['RS','Rokhaya Sow','Product Manager · Dakar',"La formation marketing digital a boosté mes compétences. Les formateurs sont disponibles et les contenus toujours à jour."],
            ['MB','Moussa Ba','Data Analyst · Abidjan',"Les cours de data science sont d'une qualité rare. J'ai pu décrocher un poste en 6 mois. LearnHub m'a donné les clés du succès."],
        ];
        @endphp
        <div class="testi-wrap rv" id="testi-outer">
            <div class="testi-track" id="testi-track">
                @foreach($testis as $t)
                <div class="testi-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-txt">{{ $t[3] }}</p>
                    <div class="t-auth">
                        <div class="t-av">{{ $t[0] }}</div>
                        <div>
                            <div class="t-name">{{ $t[1] }}</div>
                            <div class="t-role">{{ $t[2] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="testi-nav">
            <button class="car-btn" id="ti-prev" aria-label="Précédent"><i class="bi bi-arrow-left"></i></button>
            <div class="car-dots" id="ti-dots"></div>
            <button class="car-btn" id="ti-next" aria-label="Suivant"><i class="bi bi-arrow-right"></i></button>
        </div>
    </div>
</section>

{{-- ══ CTA FINAL — fond blanc, card premium ══ --}}
<section class="cta-s" id="contact">
    <div class="container">
        <div class="cta-in rv">
            <div class="cta-tag"><i class="bi bi-rocket-takeoff"></i> Commencez maintenant</div>
            <h2 class="cta-title">
                Commencez votre apprentissage<br>
                <span class="grad-text">aujourd'hui.</span>
            </h2>
            <p class="cta-sub">
                Rejoignez 20 000+ apprenants qui transforment leur carrière.
                Accès immédiat, aucune carte requise.
            </p>
            <div class="cta-acts">
                <a href="{{ route('register') }}" class="btn btn-cta-p btn-lg">
                    <i class="bi bi-person-plus"></i> Créer mon compte gratuit
                </a>
                <a href="{{ route('courses.index') }}" class="btn btn-cta-o btn-lg">
                    <i class="bi bi-search"></i> Explorer les cours
                </a>
            </div>
            <div class="cta-trust">
                <div class="cta-trust-item"><i class="bi bi-check-circle-fill"></i> Gratuit pour commencer</div>
                <div class="cta-trust-item"><i class="bi bi-check-circle-fill"></i> Sans carte bancaire</div>
                <div class="cta-trust-item"><i class="bi bi-check-circle-fill"></i> Accès immédiat</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ NEWSLETTER ══ --}}
<section class="nl-s" id="newsletter">
    <div class="container">
        <div class="nl-box rv">
            <div class="tag" style="justify-content:center"><i class="bi bi-envelope-check"></i> Newsletter</div>
            <h2 class="section-title">Restez informé des <span class="grad-text">nouveautés</span></h2>
            <p class="section-sub" style="margin:0 auto">Recevez chaque semaine les meilleurs cours et offres exclusives dans votre boîte mail.</p>
            <div class="nl-form">
                <input type="email" class="nl-input" placeholder="votre@email.com">
                <button class="nl-btn"><i class="bi bi-send"></i> S'inscrire</button>
            </div>
            <div class="nl-note">🔒 Aucun spam. Désabonnement en 1 clic.</div>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer class="footer">
    <div class="footer-grid">
        <div>
            <a href="/" class="f-logo">
                <div class="nav-logo-ic" style="width:32px;height:32px;font-size:.9rem">
                    <i class="bi bi-mortarboard-fill" style="color:#fff"></i>
                </div>
                <span style="font-size:1.05rem;font-weight:800;color:#fff">LearnHub</span>
            </a>
            <p class="f-desc">La plateforme d'apprentissage qui propulse votre carrière vers les compétences du futur.</p>
            <div class="f-socials">
                @php $socials = ['twitter-x','linkedin','instagram','youtube','github']; @endphp
                @foreach($socials as $s)
                <a href="#" class="f-soc" aria-label="{{ $s }}"><i class="bi bi-{{ $s }}"></i></a>
                @endforeach
            </div>
        </div>
        <div>
            <div class="f-col-ttl">Plateforme</div>
            @php $pLinks = ['Catalogue des cours','Formateurs','Certifications','Blog','Tarifs']; @endphp
            <ul class="f-links">
                @foreach($pLinks as $l)
                <li><a href="#" class="f-link">{{ $l }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <div class="f-col-ttl">Entreprise</div>
            @php $cLinks = ['À propos','Carrières','Presse','Partenaires','Contact']; @endphp
            <ul class="f-links">
                @foreach($cLinks as $l)
                <li><a href="#" class="f-link">{{ $l }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <div class="f-col-ttl">Légal</div>
            @php $lLinks = ["Conditions d'utilisation",'Confidentialité','Cookies','Accessibilité']; @endphp
            <ul class="f-links">
                @foreach($lLinks as $l)
                <li><a href="#" class="f-link">{{ $l }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="f-bottom">
        <div class="f-copy">© {{ date('Y') }} LearnHub. Tous droits réservés.</div>
        <div class="f-dev">
            <i class="bi bi-code-slash" style="color:#8b5cf6"></i>
            Développé par <strong>Pape Seck</strong>
        </div>
        <div class="f-legal">
            <a href="#">CGU</a>
            <a href="#">Confidentialité</a>
            <a href="#">Cookies</a>
        </div>
    </div>
</footer>

<script>
/* ══ NAVBAR — scroll + auto-hide ══ */
(function () {
    var nav       = document.getElementById('nav');
    var lastY     = 0;
    var ticking   = false;
    var THRESHOLD = 80; /* px de scroll avant de cacher */

    function updateNav() {
        var y = window.scrollY;

        /* Glassmorphism */
        nav.classList.toggle('sc', y > 20);

        /* Auto-hide : masque en descendant, réapparaît en remontant */
        if (y > THRESHOLD) {
            if (y > lastY) {
                nav.classList.add('hidden');    /* descendant */
            } else {
                nav.classList.remove('hidden'); /* remontant */
            }
        } else {
            nav.classList.remove('hidden');     /* en haut de page */
        }

        lastY    = y;
        ticking  = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(updateNav);
            ticking = true;
        }
    }, { passive: true });

    updateNav(); /* initialisation */
})();

/* ══ MOBILE MENU — burger corrigé ══ */
(function () {
    var burger    = document.getElementById('burger');
    var mobOv     = document.getElementById('mob-ov');
    var mobDrawer = document.getElementById('mob-drawer');
    var mobClose  = document.getElementById('mob-close');
    var isOpen    = false;

    function openMob() {
        isOpen = true;
        burger.classList.add('on');
        burger.setAttribute('aria-expanded', 'true');
        mobOv.classList.add('on');
        mobDrawer.classList.add('on');
        mobDrawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }

    function closeMob() {
        if (!isOpen) return;
        isOpen = false;
        burger.classList.remove('on');
        burger.setAttribute('aria-expanded', 'false');
        mobOv.classList.remove('on');
        mobDrawer.classList.remove('on');
        mobDrawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

    /* Burger toggle */
    burger.addEventListener('click', function (e) {
        e.stopPropagation();
        isOpen ? closeMob() : openMob();
    });

    /* ✅ Bouton X */
    mobClose.addEventListener('click', function (e) {
        e.stopPropagation();
        closeMob();
    });

    /* ✅ Overlay */
    mobOv.addEventListener('click', closeMob);

    /* ✅ Tous les liens du drawer */
    document.querySelectorAll('[data-mob-close]').forEach(function (el) {
        el.addEventListener('click', closeMob);
    });

    /* Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeMob();
    });
})();

/* ══ REVEAL on scroll ══ */
(function () {
    var els = document.querySelectorAll('.rv');
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -32px 0px' });
    els.forEach(function (el) { obs.observe(el); });
})();

/* ══ COUNT-UP ══ */
(function () {
    function countUp(el, target, dur) {
        var start   = performance.now();
        var isLarge = target >= 1000;
        (function run(now) {
            var p   = Math.min((now - start) / dur, 1);
            var val = Math.round((1 - Math.pow(1 - p, 3)) * target);
            el.textContent = isLarge
                ? (val >= 1000 ? (val / 1000).toFixed(val % 1000 === 0 ? 0 : 1) + 'k+' : val + '+')
                : val + '+';
            if (p < 1) requestAnimationFrame(run);
        })(start);
    }

    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting && e.target.dataset.count) {
                countUp(e.target, +e.target.dataset.count, 1400);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('[data-count]').forEach(function (el) { obs.observe(el); });
})();

/* ══ CARROUSEL générique ══ */
function makeCarousel(trackId, prevId, nextId, dotsId) {
    var track = document.getElementById(trackId);
    var prev  = document.getElementById(prevId);
    var next  = document.getElementById(nextId);
    var dotsC = document.getElementById(dotsId);
    if (!track || !prev || !next || !dotsC) return;

    var cur   = 0;
    var autoT = null;

    function pv() {
        var w = track.parentElement.offsetWidth;
        return w < 580 ? 1 : w < 920 ? 2 : 3;
    }
    function pgs() { return Math.max(1, track.children.length - pv() + 1); }

    function buildDots() {
        dotsC.innerHTML = '';
        for (var i = 0; i < pgs(); i++) {
            (function (idx) {
                var d = document.createElement('div');
                d.className = 'car-dot' + (idx === cur ? ' on' : '');
                d.addEventListener('click', function () { go(idx); resetAuto(); });
                dotsC.appendChild(d);
            })(i);
        }
    }

    function go(idx) {
        cur = Math.max(0, Math.min(idx, pgs() - 1));
        var pvN  = pv();
        var gap  = 20;
        var totW = track.parentElement.offsetWidth;
        var cw   = (totW - gap * (pvN - 1)) / pvN;
        track.style.transform = 'translateX(-' + (cur * (cw + gap)) + 'px)';
        dotsC.querySelectorAll('.car-dot').forEach(function (d, i) {
            d.classList.toggle('on', i === cur);
        });
    }

    prev.addEventListener('click', function () { go(cur - 1); resetAuto(); });
    next.addEventListener('click', function () { go(cur + 1 >= pgs() ? 0 : cur + 1); resetAuto(); });

    /* Swipe tactile */
    var tx0 = 0;
    track.addEventListener('touchstart', function (e) { tx0 = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend',   function (e) {
        var dx = tx0 - e.changedTouches[0].clientX;
        if (Math.abs(dx) > 40) { go(dx > 0 ? (cur + 1 >= pgs() ? 0 : cur + 1) : cur - 1); resetAuto(); }
    }, { passive: true });

    function resetAuto() {
        clearInterval(autoT);
        autoT = setInterval(function () { go(cur + 1 >= pgs() ? 0 : cur + 1); }, 4500);
    }

    buildDots();
    go(0);
    resetAuto();
    window.addEventListener('resize', function () { buildDots(); go(cur); });
}

makeCarousel('car-track',   'car-prev', 'car-next', 'car-dots');
makeCarousel('testi-track', 'ti-prev',  'ti-next',  'ti-dots');
</script>
</body>
</html>