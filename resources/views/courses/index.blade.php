{{-- resources/views/courses/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catalogue des cours - LearnHub</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;overflow-x:hidden}
body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:#f7f5ff;color:#1a1a2e;overflow-x:hidden;line-height:1.6;-webkit-text-size-adjust:100%}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
ul{list-style:none}
:root{
    --p:#7c3aed;--p-dk:#5b21b6;--p-lt:#8b5cf6;--p-xl:#a78bfa;
    --p-gl:rgba(124,58,237,.1);
    --acc:#06b6d4;--ok:#10b981;--warn:#f59e0b;--err:#ef4444;
    --t1:#0f0823;--t2:#374151;--t3:#6b7280;--t4:#9ca3af;
    --bg:#f7f5ff;--surface:#ffffff;--border:#ede9fe;
    --nav-h:62px;--r:14px;--r-sm:10px;--r-xs:8px;
    --sh:0 4px 20px rgba(124,58,237,.1),0 1px 4px rgba(0,0,0,.05);
    --sh-card:0 2px 12px rgba(124,58,237,.08),0 1px 3px rgba(0,0,0,.04);
    --sh-card-h:0 20px 48px rgba(124,58,237,.18),0 4px 16px rgba(0,0,0,.08);
    --tr:all .22s cubic-bezier(.4,0,.2,1);
    --spring:cubic-bezier(.22,1,.36,1);
}
.grad-text{background:linear-gradient(135deg,var(--p),var(--acc));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}

/* ── NAVBAR ── */
.navbar{position:fixed;top:0;left:0;right:0;z-index:900;padding:0 20px;background:rgba(247,245,255,.88);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid rgba(124,58,237,.1);box-shadow:0 1px 0 rgba(0,0,0,.05);transition:transform .35s var(--spring),box-shadow .25s}
.navbar.hidden{transform:translateY(-100%)}
.navbar.scrolled{background:rgba(247,245,255,.97);box-shadow:0 4px 24px rgba(124,58,237,.1)}
.nav-in{max-width:1200px;margin:0 auto;display:flex;align-items:center;height:var(--nav-h);gap:1.5rem}
.nav-logo{display:flex;align-items:center;gap:9px;text-decoration:none;flex-shrink:0}
.nav-logo-ic{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--p),var(--acc));display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 12px rgba(124,58,237,.35)}
.nav-logo-n{font-size:1.1rem;font-weight:800;color:var(--t1)}
.nav-links{display:flex;align-items:center;gap:.15rem;flex:1}
.nav-link{padding:.4rem .8rem;border-radius:var(--r-xs);font-size:.86rem;font-weight:500;color:var(--t2);transition:var(--tr);text-decoration:none}
.nav-link:hover{background:var(--p-gl);color:var(--p)}
.nav-link.active{background:var(--p-gl);color:var(--p);font-weight:600}
.nav-acts{display:flex;align-items:center;gap:.5rem;margin-left:auto}
.nav-btn{padding:.44rem 1rem;border-radius:var(--r-xs);font-size:.86rem;font-weight:600;transition:var(--tr);text-decoration:none;display:inline-flex;align-items:center;gap:5px}
.nav-btn-ghost{color:var(--t2);background:transparent}
.nav-btn-ghost:hover{color:var(--p);background:var(--p-gl)}
.nav-btn-solid{background:var(--p);color:#fff;box-shadow:0 2px 8px rgba(124,58,237,.3)}
.nav-btn-solid:hover{background:var(--p-dk);transform:translateY(-1px)}
.nav-burger{display:none;flex-direction:column;gap:4px;background:none;border:none;cursor:pointer;padding:7px;border-radius:var(--r-xs);transition:var(--tr);flex-shrink:0}
.nav-burger span{display:block;width:20px;height:2px;background:var(--t1);border-radius:2px;transition:var(--tr)}
.nav-burger.on span:nth-child(1){transform:translateY(6px) rotate(45deg)}
.nav-burger.on span:nth-child(2){opacity:0;transform:scaleX(0)}
.nav-burger.on span:nth-child(3){transform:translateY(-6px) rotate(-45deg)}

/* ── MOBILE DRAWER ── */
.mob-ov{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:990;opacity:0;pointer-events:none;transition:opacity .28s}
.mob-ov.on{opacity:1;pointer-events:all}
.mob-drawer{position:fixed;top:0;right:0;bottom:0;width:min(300px,86vw);background:#fff;z-index:991;transform:translateX(100%);transition:transform .32s var(--spring);box-shadow:-16px 0 50px rgba(0,0,0,.16);display:flex;flex-direction:column}
.mob-drawer.on{transform:translateX(0)}
.mob-hd{display:flex;justify-content:space-between;align-items:center;padding:.85rem 1.1rem;border-bottom:1px solid var(--border);flex-shrink:0}
.mob-close{width:32px;height:32px;border-radius:var(--r-xs);background:var(--bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--t2);font-size:1.1rem}
.mob-body{flex:1;overflow-y:auto;padding:.65rem .9rem}
.mob-lnk{display:flex;align-items:center;gap:9px;padding:.65rem .9rem;border-radius:9px;font-size:.88rem;font-weight:500;color:var(--t2);transition:var(--tr);text-decoration:none}
.mob-lnk:hover,.mob-lnk.active{background:var(--p-gl);color:var(--p)}
.mob-div{height:1px;background:var(--border);margin:.55rem 0}
.mob-ft{padding:.9rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.45rem;flex-shrink:0}

/* ── SPACER navbar fixed ── */
.nav-spacer{height:var(--nav-h)}

/* ── HERO ── */
.hero{position:relative;overflow:hidden;background:linear-gradient(155deg,#07030f 0%,#130845 45%,#071830 100%);padding:clamp(2.5rem,7vh,4.5rem) 20px clamp(2rem,5vh,3.5rem);display:flex;align-items:center;min-height:min(540px,90vh);max-height:90vh}
.hero-orb{position:absolute;border-radius:50%;filter:blur(65px);pointer-events:none;animation:orb 14s ease-in-out infinite}
.h-o1{width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.38) 0%,transparent 70%);top:-150px;left:-100px}
.h-o2{width:350px;height:350px;background:radial-gradient(circle,rgba(6,182,212,.25) 0%,transparent 70%);bottom:-80px;right:5%;animation-delay:-7s}
.h-o3{width:220px;height:220px;background:radial-gradient(circle,rgba(16,185,129,.2) 0%,transparent 70%);top:40%;left:45%;animation-delay:-3.5s}
@keyframes orb{0%,100%{transform:scale(1) translate(0,0)}50%{transform:scale(1.06) translate(-12px,-16px)}}
.hero-grid{position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.045) 1px,transparent 1px);background-size:28px 28px;pointer-events:none}
.hero-in{position:relative;z-index:1;max-width:1200px;margin:0 auto;width:100%;display:grid;grid-template-columns:1fr auto;gap:2rem;align-items:center}
.hero-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(124,58,237,.2);color:#c4b5fd;border:1px solid rgba(124,58,237,.3);border-radius:999px;padding:.28rem .9rem;font-size:.68rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:1rem}
.hero-badge-dot{width:6px;height:6px;border-radius:50%;background:var(--acc);animation:blink 2s ease-in-out infinite;flex-shrink:0}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.hero-h1{font-size:clamp(1.75rem,4vw,3.2rem);font-weight:800;color:#fff;line-height:1.1;letter-spacing:-.5px;margin-bottom:.85rem}
.hero-desc{font-size:clamp(.88rem,1.5vw,1.02rem);color:rgba(255,255,255,.52);line-height:1.78;max-width:500px;margin-bottom:1.75rem}
.hero-ctas{display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.75rem}
.hero-cta-main{display:inline-flex;align-items:center;gap:7px;padding:.82rem 1.6rem;background:linear-gradient(135deg,var(--p),var(--p-lt));color:#fff;border-radius:var(--r-sm);font:700 .95rem/1 inherit;border:none;cursor:pointer;box-shadow:0 8px 30px rgba(124,58,237,.4);transition:var(--tr);text-decoration:none;white-space:nowrap}
.hero-cta-main:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(124,58,237,.5)}
.hero-cta-ghost{display:inline-flex;align-items:center;gap:7px;padding:.82rem 1.4rem;background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.18);border-radius:var(--r-sm);font:600 .9rem/1 inherit;transition:var(--tr);text-decoration:none;white-space:nowrap}
.hero-cta-ghost:hover{background:rgba(255,255,255,.14);transform:translateY(-2px)}
.hero-stats{display:flex;gap:1.2rem;flex-wrap:wrap}
.hstat{display:flex;flex-direction:column;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:11px;padding:.72rem 1rem;min-width:82px;text-align:center;transition:var(--tr);cursor:default}
.hstat:hover{background:rgba(255,255,255,.12);transform:translateY(-2px)}
.hstat-v{font-size:1.4rem;font-weight:800;color:#fff;line-height:1}
.hstat-l{font-size:.65rem;color:rgba(255,255,255,.42);margin-top:2px}
.hero-visual{display:flex;flex-direction:column;gap:.75rem;min-width:200px}
.hv-card{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:.85rem 1rem;backdrop-filter:blur(10px);display:flex;align-items:center;gap:.8rem;transition:var(--tr)}
.hv-card:hover{background:rgba(255,255,255,.12)}
.hv-ic{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.hv-title{font-size:.8rem;font-weight:600;color:#fff;margin-bottom:1px}
.hv-sub{font-size:.7rem;color:rgba(255,255,255,.44)}

/* ── SEARCH ── */
.search-section{background:#fff;border-bottom:1px solid var(--border);padding:.85rem 20px;position:sticky;top:var(--nav-h);z-index:800;box-shadow:0 2px 12px rgba(0,0,0,.05)}
.search-in{max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:.65rem;flex-wrap:wrap}
.search-bar-wrap{flex:1;min-width:0;position:relative;max-width:580px}
.search-bar-ic{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--t4);font-size:1rem;pointer-events:none;transition:color .2s}
.search-bar-wrap:focus-within .search-bar-ic{color:var(--p)}
.search-bar{width:100%;padding:.72rem 1rem .72rem 2.85rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font:inherit;font-size:.9rem;color:var(--t1);background:#faf8ff;outline:none;transition:border-color .2s,box-shadow .2s}
.search-bar:focus{border-color:var(--p);background:#fff;box-shadow:0 0 0 3px rgba(124,58,237,.12)}
.search-btn{display:flex;align-items:center;gap:6px;padding:.72rem 1.25rem;background:linear-gradient(135deg,var(--p),var(--p-lt));color:#fff;border:none;border-radius:var(--r-sm);font:600 .88rem/1 inherit;cursor:pointer;white-space:nowrap;flex-shrink:0;transition:var(--tr);box-shadow:0 2px 10px rgba(124,58,237,.28)}
.search-btn:hover{transform:translateY(-1px)}
.sort-select{padding:.68rem 2rem .68rem .85rem;border:1.5px solid var(--border);border-radius:var(--r-sm);background:#fff;font:inherit;font-size:.85rem;color:var(--t2);-webkit-appearance:none;outline:none;cursor:pointer;transition:border-color .2s}
.sort-select:focus{border-color:var(--p)}

/* ── CATEGORIES ── */
.cats-section{padding:1rem 20px;background:#fff;border-bottom:1px solid var(--border)}
.cats-in{max-width:1200px;margin:0 auto}
.cats-scroll{display:flex;gap:.55rem;overflow-x:auto;scrollbar-width:none;padding-bottom:2px}
.cats-scroll::-webkit-scrollbar{display:none}
.cat-pill{display:flex;align-items:center;gap:6px;padding:.45rem 1rem;border:1.5px solid var(--border);border-radius:999px;background:#fff;color:var(--t2);font:500 .82rem/1 inherit;white-space:nowrap;flex-shrink:0;cursor:pointer;transition:var(--tr);text-decoration:none;user-select:none}
.cat-pill:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}
.cat-pill.active{background:var(--p);color:#fff;border-color:var(--p);box-shadow:0 4px 14px rgba(124,58,237,.32)}

/* ── TRENDING ── */
.trending-section{background:#fff;border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:2.5rem 20px}
.ts-in{max-width:1200px;margin:0 auto}
.ts-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap}
.ts-title{font-size:1.3rem;font-weight:800;color:var(--t1);display:flex;align-items:center;gap:.55rem}
.ts-title i{color:var(--warn)}
.ts-link{font-size:.84rem;color:var(--p);font-weight:600;text-decoration:none;display:flex;align-items:center;gap:4px}
.ts-track-wrap{overflow:hidden;border-radius:var(--r)}
.ts-track{display:flex;gap:1.1rem;transition:transform .46s var(--spring);will-change:transform}
.ts-card{flex:0 0 calc(25% - 0.83rem);background:var(--bg);border:1.5px solid var(--border);border-radius:var(--r);padding:1rem;display:flex;align-items:flex-start;gap:.85rem;transition:var(--tr);cursor:pointer}
.ts-card:hover{border-color:var(--p);background:#fff;transform:translateY(-3px);box-shadow:var(--sh)}
.ts-rank{font-size:1.6rem;font-weight:800;color:var(--border);line-height:1;flex-shrink:0;width:32px;text-align:right;transition:color .18s}
.ts-card:hover .ts-rank{color:var(--p-xl)}
.ts-emoji{width:44px;height:44px;border-radius:10px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.ts-info{min-width:0}
.ts-name{font-size:.84rem;font-weight:700;color:var(--t1);margin-bottom:.2rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.35}
.ts-card:hover .ts-name{color:var(--p)}
.ts-meta{font-size:.72rem;color:var(--t4)}
.ts-nav{display:flex;align-items:center;justify-content:center;gap:.85rem;margin-top:1.5rem}
.ts-btn{width:38px;height:38px;border-radius:50%;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.9rem;color:var(--t3);transition:var(--tr)}
.ts-btn:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}
.ts-dots{display:flex;gap:.4rem;align-items:center}
.ts-dot{width:7px;height:7px;border-radius:50%;background:var(--border);cursor:pointer;transition:var(--tr)}
.ts-dot.on{background:var(--p);width:18px;border-radius:999px}

/* ── MAIN ── */
.main{padding:2rem 20px 4rem}
.main-in{max-width:1200px;margin:0 auto}
.results-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem}
.results-count{font-size:.88rem;color:var(--t3);font-weight:500}
.results-count strong{color:var(--t1);font-weight:700}
.view-toggle{display:flex;gap:.3rem}
.view-btn{width:34px;height:34px;border-radius:var(--r-xs);border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;color:var(--t4);font-size:.9rem;cursor:pointer;transition:var(--tr)}
.view-btn.active,.view-btn:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}

/* ── COURSES GRID ── */
.courses-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem}
.course-card{background:var(--surface);border-radius:var(--r);border:1px solid var(--border);overflow:hidden;display:flex;flex-direction:column;transition:transform .25s var(--spring),box-shadow .25s var(--spring),border-color .2s;cursor:pointer;position:relative;min-width:0}
.course-card:hover{transform:translateY(-6px);box-shadow:var(--sh-card-h);border-color:rgba(124,58,237,.2)}
.card-thumb{position:relative;overflow:hidden;aspect-ratio:16/10;background:linear-gradient(135deg,#1e1b4b,#312e81);flex-shrink:0}
.card-thumb-img{width:100%;height:100%;object-fit:cover;transition:transform .45s var(--spring)}
.card-thumb-ico{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.8rem}
.course-card:hover .card-thumb-img{transform:scale(1.06)}
.course-card:hover .card-thumb-ico{transform:scale(1.06)}
.card-thumb::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(9,4,35,.65) 100%);pointer-events:none;opacity:0;transition:opacity .25s}
.course-card:hover .card-thumb::after{opacity:1}
.card-badges{position:absolute;top:10px;left:10px;display:flex;gap:.35rem;flex-wrap:wrap;z-index:1}
.card-badge{padding:.2rem .55rem;border-radius:999px;font-size:.62rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;backdrop-filter:blur(8px)}
.cb-cat{background:rgba(124,58,237,.85);color:#fff}
.cb-new{background:rgba(16,185,129,.85);color:#fff}
.cb-pop{background:rgba(245,158,11,.9);color:#fff}
.cb-free{background:rgba(6,182,212,.85);color:#fff}
.card-price-badge{position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,.7);color:#fff;backdrop-filter:blur(8px);border-radius:6px;padding:.22rem .6rem;font-size:.78rem;font-weight:700;z-index:1}
.card-action-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .22s;z-index:2}
.course-card:hover .card-action-overlay{opacity:1}
.card-quick-btn{background:rgba(255,255,255,.95);color:var(--p);border:none;border-radius:999px;padding:.55rem 1.1rem;font:600 .82rem/1 inherit;cursor:pointer;display:flex;align-items:center;gap:5px;box-shadow:0 4px 20px rgba(0,0,0,.3);transform:translateY(8px);transition:transform .22s var(--spring)}
.course-card:hover .card-quick-btn{transform:translateY(0)}
.card-body{padding:1rem;flex:1;display:flex;flex-direction:column;gap:.5rem}
.card-level{display:inline-flex;align-items:center;gap:4px;font-size:.66rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px}
.lvl-debutant{color:var(--ok)}.lvl-intermediaire{color:var(--warn)}.lvl-avance{color:var(--err)}
.card-title{font-size:.92rem;font-weight:700;color:var(--t1);line-height:1.38;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color .18s;min-height:2.76em}
.course-card:hover .card-title{color:var(--p)}
.card-author{display:flex;align-items:center;gap:7px;font-size:.76rem;color:var(--t3)}
.card-av{width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,var(--p),var(--acc));display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;color:#fff;flex-shrink:0}
.card-rating{display:flex;align-items:center;gap:5px}
.rating-stars{color:#f59e0b;font-size:.78rem;letter-spacing:.5px}
.rating-val{font-size:.78rem;font-weight:700;color:var(--t1)}
.rating-count{font-size:.72rem;color:var(--t4)}
.card-foot{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1rem;border-top:1px solid var(--border);flex-shrink:0}
.card-price{font-size:1rem;font-weight:800;color:var(--p)}
.card-price-free{font-size:1rem;font-weight:800;color:var(--ok)}
.card-meta{display:flex;align-items:center;gap:.65rem;font-size:.72rem;color:var(--t4)}
.card-students{display:flex;align-items:center;gap:4px;font-size:.73rem;color:var(--t4)}

/* ── EMPTY STATE ── */
.empty-state{text-align:center;padding:5rem 1rem;grid-column:1/-1}
.empty-icon{font-size:4rem;margin-bottom:1.25rem;opacity:.4}
.empty-title{font-size:1.4rem;font-weight:700;color:var(--t1);margin-bottom:.5rem}
.empty-desc{font-size:.9rem;color:var(--t3);max-width:400px;margin:0 auto 1.5rem;line-height:1.7}
.btn-reset{display:inline-flex;align-items:center;gap:6px;padding:.72rem 1.5rem;background:var(--p);color:#fff;border:none;border-radius:var(--r-sm);font:600 .9rem/1 inherit;cursor:pointer;transition:var(--tr);box-shadow:0 4px 14px rgba(124,58,237,.3);text-decoration:none}
.btn-reset:hover{background:var(--p-dk);transform:translateY(-1px)}

/* ── PAGINATION ── */
.pag-wrap{display:flex;justify-content:center;margin-top:2.5rem}
.pag-wrap .pagination{display:flex;gap:.35rem;align-items:center;flex-wrap:wrap;justify-content:center}
.pag-wrap .page-item .page-link{display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:var(--r-xs);border:1.5px solid var(--border);background:#fff;color:var(--t2);font-size:.86rem;text-decoration:none;transition:var(--tr)}
.pag-wrap .page-item.active .page-link{background:var(--p);border-color:var(--p);color:#fff}
.pag-wrap .page-item .page-link:hover{border-color:var(--p);color:var(--p);background:var(--p-gl)}
.pag-wrap .page-item.disabled .page-link{opacity:.4;cursor:not-allowed}

/* ── FOOTER ── */
.footer{background:#080516;padding:50px 20px 24px;border-top:1px solid rgba(255,255,255,.06)}
.footer-grid{display:grid;grid-template-columns:2fr repeat(3,1fr);gap:2rem;max-width:1200px;margin:0 auto 2rem}
.f-logo{display:flex;align-items:center;gap:9px;text-decoration:none;margin-bottom:.8rem}
.f-desc{font-size:.8rem;color:rgba(255,255,255,.32);line-height:1.75;max-width:230px}
.f-socials{display:flex;gap:.45rem;margin-top:1rem}
.f-soc{width:31px;height:31px;border-radius:7px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.42);font-size:.8rem;transition:var(--tr);text-decoration:none}
.f-soc:hover{background:var(--p);border-color:var(--p);color:#fff;transform:translateY(-2px)}
.f-col-ttl{font-size:.75rem;font-weight:700;color:rgba(255,255,255,.76);text-transform:uppercase;letter-spacing:1px;margin-bottom:.85rem}
.f-links{display:flex;flex-direction:column;gap:.48rem}
.f-link{font-size:.8rem;color:rgba(255,255,255,.32);text-decoration:none;transition:color .18s}
.f-link:hover{color:rgba(255,255,255,.74)}
.f-bottom{max-width:1200px;margin:0 auto;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.7rem}
.f-copy{font-size:.76rem;color:rgba(255,255,255,.24)}
.f-dev{font-size:.76rem;color:rgba(255,255,255,.34);display:flex;align-items:center;gap:5px}
.f-dev strong{color:#c4b5fd;font-weight:700}

/* ── ANIMATIONS ── */
.rv{opacity:0;transform:translateY(20px);transition:opacity .55s ease,transform .55s ease}
.rv.in{opacity:1;transform:translateY(0)}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ── RESPONSIVE ── */
@media(max-width:1100px){
    .courses-grid{grid-template-columns:repeat(3,1fr)}
    .ts-card{flex:0 0 calc(33.333% - 0.74rem)}
    .footer-grid{grid-template-columns:1fr 1fr}
    .hero-visual{display:none}
    .hero-in{grid-template-columns:1fr}
}
@media(max-width:768px){
    .courses-grid{grid-template-columns:repeat(2,1fr);gap:.75rem}
    .ts-card{flex:0 0 calc(50% - 0.55rem)}
    .hero{padding:2rem 20px 1.75rem;min-height:auto;max-height:none}
    .hero-h1{font-size:1.75rem}
    .sort-select{display:none}
    .nav-links,.nav-acts{display:none}
    .nav-burger{display:flex}
    .results-header{flex-direction:column;align-items:flex-start}
    .footer-grid{grid-template-columns:1fr 1fr}
    /* Mobile card tweaks */
    .card-body{padding:.75rem}
    .card-foot{padding:.6rem .75rem}
    .card-title{font-size:.85rem;min-height:2.55em}
    .card-price,.card-price-free{font-size:.92rem}
}
@media(max-width:520px){
    .courses-grid{grid-template-columns:repeat(2,1fr);gap:.6rem}
    .ts-card{flex:0 0 100%}
    .hero-stats{grid-template-columns:repeat(3,1fr);display:grid;gap:.55rem}
    .hstat{padding:.55rem .5rem;min-width:0}
    .cats-section,.search-section{padding:.75rem 14px}
    .main{padding:1.25rem 14px 3rem}
    .footer-grid{grid-template-columns:1fr}
    /* 2 cards on mobile - compact premium */
    .card-thumb{aspect-ratio:4/3}
    .card-body{padding:.65rem}
    .card-foot{padding:.55rem .65rem}
    .card-title{font-size:.8rem;min-height:2.4em;-webkit-line-clamp:2}
    .card-level{font-size:.6rem}
    .card-author{display:none}
    .card-rating{gap:3px}
    .rating-stars{font-size:.72rem}
    .rating-val{font-size:.72rem}
    .rating-count{display:none}
    .card-price,.card-price-free{font-size:.88rem}
    .card-students{font-size:.68rem}
    .card-badges{top:6px;left:6px;gap:.25rem}
    .card-badge{font-size:.56rem;padding:.15rem .42rem}
    .card-price-badge{bottom:6px;right:6px;font-size:.7rem;padding:.18rem .48rem}
    .card-quick-btn{font-size:.75rem;padding:.45rem .85rem}
    .hero-ctas{gap:.55rem}
    .hero-cta-main,.hero-cta-ghost{padding:.7rem 1.1rem;font-size:.85rem}
}
@media(max-width:380px){
    .courses-grid{gap:.5rem}
    .card-body{padding:.55rem}
    .card-foot{padding:.5rem .55rem}
    .card-title{font-size:.76rem}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-in">
        <a href="/" class="nav-logo">
            <div class="nav-logo-ic"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
            <span class="nav-logo-n">LearnHub</span>
        </a>
        <div class="nav-links">
            <a href="/" class="nav-link">Accueil</a>
            <a href="{{ route('courses.index') }}" class="nav-link active">Catalogue</a>
            <a href="#" class="nav-link">Formateurs</a>
            <a href="#" class="nav-link">Contact</a>
        </div>
        <div class="nav-acts">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-btn nav-btn-ghost">
                    <i class="bi bi-grid-3x3-gap" style="font-size:.82rem"></i> Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="nav-btn nav-btn-ghost" style="cursor:pointer;border:none;background:none;font:inherit">Deconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-btn nav-btn-ghost">Connexion</a>
                <a href="{{ route('register') }}" class="nav-btn nav-btn-solid">S'inscrire</a>
            @endauth
        </div>
        <button class="nav-burger" id="burger" aria-label="Menu" type="button">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
<div class="nav-spacer"></div>

<!-- MOBILE OVERLAY + DRAWER -->
<div class="mob-ov" id="mob-ov"></div>
<div class="mob-drawer" id="mob-drawer">
    <div class="mob-hd">
        <a href="/" class="nav-logo">
            <div class="nav-logo-ic" style="width:30px;height:30px;font-size:.88rem"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
            <span class="nav-logo-n">LearnHub</span>
        </a>
        <button class="mob-close" id="mob-close" type="button"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="mob-body">
        <a href="/" class="mob-lnk"><i class="bi bi-house" style="color:var(--p)"></i> Accueil</a>
        <a href="{{ route('courses.index') }}" class="mob-lnk active"><i class="bi bi-collection-play" style="color:var(--p)"></i> Catalogue</a>
        <a href="#" class="mob-lnk"><i class="bi bi-person-workspace" style="color:var(--p)"></i> Formateurs</a>
        <a href="#" class="mob-lnk"><i class="bi bi-chat-dots" style="color:var(--p)"></i> Contact</a>
        <div class="mob-div"></div>
        @auth
            <a href="{{ route('dashboard') }}" class="mob-lnk"><i class="bi bi-grid-3x3-gap" style="color:var(--p)"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mob-lnk"><i class="bi bi-box-arrow-in-right" style="color:var(--p)"></i> Connexion</a>
        @endauth
    </div>
    @guest
        <div class="mob-ft">
            <a href="{{ route('login') }}" style="display:flex;justify-content:center" class="nav-btn nav-btn-ghost">Connexion</a>
            <a href="{{ route('register') }}" style="display:flex;justify-content:center" class="nav-btn nav-btn-solid">S'inscrire</a>
        </div>
    @endguest
</div>

<!-- HERO -->
<section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-orb h-o1"></div>
    <div class="hero-orb h-o2"></div>
    <div class="hero-orb h-o3"></div>
    <div class="hero-in">
        <div>
            <div class="hero-badge"><span class="hero-badge-dot"></span> Decouvrez notre catalogue</div>
            <h1 class="hero-h1">Developpez vos<br><span class="grad-text">competences</span> des<br>aujourd'hui.</h1>
            <p class="hero-desc">{{ $courses->total() > 0 ? $courses->total() : 'Des' }} cours crees par des experts reconnus. Progressez a votre rythme.</p>
            <div class="hero-ctas">
                <a href="#courses-grid" class="hero-cta-main">
                    <i class="bi bi-collection-play-fill"></i> Explorer les cours
                </a>
                <a href="{{ route('register') }}" class="hero-cta-ghost">
                    <i class="bi bi-person-plus"></i> Creer un compte
                </a>
            </div>
            <div class="hero-stats">
                <div class="hstat">
                    <div class="hstat-v" data-count="{{ $courses->total() }}">{{ $courses->total() }}</div>
                    <div class="hstat-l">Cours</div>
                </div>
                <div class="hstat">
                    <div class="hstat-v" data-count="5000">5k+</div>
                    <div class="hstat-l">Etudiants</div>
                </div>
                <div class="hstat">
                    <div class="hstat-v" data-count="40">40+</div>
                    <div class="hstat-l">Formateurs</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hv-card">
                <div class="hv-ic" style="background:rgba(245,158,11,.15)">
                    <i class="bi bi-award" style="color:#fbbf24;font-size:1.2rem"></i>
                </div>
                <div><div class="hv-title">Certifications reconnues</div><div class="hv-sub">Valorisees par les entreprises</div></div>
            </div>
            <div class="hv-card">
                <div class="hv-ic" style="background:rgba(6,182,212,.15)">
                    <i class="bi bi-infinity" style="color:#22d3ee;font-size:1.2rem"></i>
                </div>
                <div><div class="hv-title">Acces illimite</div><div class="hv-sub">A vie apres inscription</div></div>
            </div>
            <div class="hv-card">
                <div class="hv-ic" style="background:rgba(16,185,129,.15)">
                    <i class="bi bi-bullseye" style="color:#34d399;font-size:1.2rem"></i>
                </div>
                <div><div class="hv-title">Apprentissage cible</div><div class="hv-sub">Progressez a votre rythme</div></div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH -->
<section class="search-section">
    <form action="{{ route('courses.index') }}" method="GET" class="search-in" id="search-form">
        <div class="search-bar-wrap">
            <i class="bi bi-search search-bar-ic"></i>
            <input type="text" name="search" class="search-bar"
                placeholder="Rechercher un cours, une competence..."
                value="{{ request('search') }}" autocomplete="off" id="search-input">
        </div>
        @if(request('categorie'))
            <input type="hidden" name="categorie" value="{{ request('categorie') }}" id="hidden-categorie">
        @endif
        @if(request('sort') && request('sort') !== 'recent')
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
        <button type="submit" class="search-btn">
            <i class="bi bi-search"></i> <span>Rechercher</span>
        </button>
        <select name="sort" class="sort-select" onchange="this.form.submit()" aria-label="Trier par">
            <option value="recent"    {{ request('sort','recent') === 'recent'    ? 'selected' : '' }}>Plus recents</option>
            <option value="populaire" {{ request('sort') === 'populaire' ? 'selected' : '' }}>Plus populaires</option>
            <option value="note"      {{ request('sort') === 'note'      ? 'selected' : '' }}>Mieux notes</option>
            <option value="prix-asc"  {{ request('sort') === 'prix-asc'  ? 'selected' : '' }}>Prix croissant</option>
            <option value="prix-desc" {{ request('sort') === 'prix-desc' ? 'selected' : '' }}>Prix decroissant</option>
        </select>
    </form>
</section>

<!-- CATEGORIES -->
<section class="cats-section">
    <div class="cats-in">
        <div class="cats-scroll" role="list">
            @php
            $currentSearch = request('search');
            $currentSort   = request('sort');
            $baseParams    = [];
            if($currentSearch) $baseParams['search'] = $currentSearch;
            if($currentSort)   $baseParams['sort']   = $currentSort;
            @endphp

            <a href="{{ route('courses.index', $baseParams) }}"
               class="cat-pill {{ !request('categorie') ? 'active' : '' }}" role="listitem">
                <i class="bi bi-grid-3x3-gap"></i> Toutes
            </a>

            @php
            $catIcons = [
                'Developpement Web' => 'bi-code-slash',
                'Mobile'            => 'bi-phone',
                'IA'                => 'bi-cpu',
                'Cybersecurite'     => 'bi-shield-lock',
                'Design'            => 'bi-palette',
                'Marketing'         => 'bi-megaphone',
                'Data'              => 'bi-bar-chart',
                'Cloud'             => 'bi-cloud',
            ];
            @endphp

            @foreach($categories as $cat)
                @php
                $catIcon   = $catIcons[$cat->name] ?? 'bi-book';
                $catParams = array_merge($baseParams, ['categorie' => $cat->id]);
                @endphp
                <a href="{{ route('courses.index', $catParams) }}"
                   class="cat-pill {{ request('categorie') == $cat->id ? 'active' : '' }}"
                   role="listitem">
                    <i class="bi {{ $catIcon }}"></i>
                    {{ $cat->name }}
                    <span style="font-size:.7rem;opacity:.7">({{ $cat->courses_count ?? 0 }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- TRENDING -->
@if(isset($trendingCourses) && $trendingCourses->count() > 0)
<section class="trending-section">
    <div class="ts-in">
        <div class="ts-header">
            <h2 class="ts-title"><i class="bi bi-fire"></i> Tendances du moment</h2>
            <a href="{{ route('courses.index', ['sort' => 'populaire']) }}" class="ts-link">
                Voir tout <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="ts-carousel">
            <div class="ts-track-wrap">
                <div class="ts-track" id="ts-track">
                    @php
                    $tsIcons  = ['bi-code-slash','bi-lightning','bi-palette','bi-phone','bi-shield-lock','bi-cpu','bi-cloud','bi-bar-chart'];
                    $tsColors = ['#7c3aed','#f59e0b','#06b6d4','#10b981','#ef4444','#8b5cf6','#0284c7','#16a34a'];
                    @endphp
                    @foreach($trendingCourses as $tsIndex => $tc)
                        @php
                        $tsIcon  = $tsIcons[$tsIndex % count($tsIcons)];
                        $tsColor = $tsColors[$tsIndex % count($tsColors)];
                        $tcFree  = ($tc->price ?? 0) == 0;
                        @endphp
                        <div class="ts-card" onclick="window.location='{{ route('courses.show', $tc) }}'">
                            <div class="ts-rank">{{ str_pad($tsIndex + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="ts-emoji" style="background:{{ $tsColor }}1a">
                                <i class="bi {{ $tsIcon }}" style="color:{{ $tsColor }};font-size:1.3rem"></i>
                            </div>
                            <div class="ts-info">
                                <div class="ts-name">{{ $tc->title }}</div>
                                <div class="ts-meta">
                                    {{ $tc->students_count ?? 0 }} etudiants
                                    @if(!$tcFree)
                                        &middot; {{ number_format($tc->price, 0, ',', ' ') }} EUR
                                    @else
                                        &middot; Gratuit
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="ts-nav">
                <button class="ts-btn" id="ts-prev" type="button"><i class="bi bi-arrow-left"></i></button>
                <div class="ts-dots" id="ts-dots"></div>
                <button class="ts-btn" id="ts-next" type="button"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</section>
@endif

<!-- MAIN COURSES -->
<main class="main">
    <div class="main-in">

        <div class="results-header rv">
            <div class="results-count">
                @if(request('search') || request('categorie'))
                    <strong>{{ $courses->total() }}</strong> resultat{{ $courses->total() > 1 ? 's' : '' }}
                    @if(request('search'))
                        pour "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if(request('categorie'))
                        @php $activeCat = $categories->firstWhere('id', request('categorie')); @endphp
                        @if($activeCat)
                            dans <strong>{{ $activeCat->name }}</strong>
                        @endif
                    @endif
                @else
                    <strong>{{ $courses->total() }}</strong> cours disponibles
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                @if(request('search') || request('categorie') || request('sort'))
                    <a href="{{ route('courses.index') }}" style="font-size:.82rem;color:var(--t3);display:flex;align-items:center;gap:4px;text-decoration:none">
                        <i class="bi bi-x-circle"></i> Reinitialiser
                    </a>
                @endif
                <div class="view-toggle">
                    <button class="view-btn active" id="grid-view" type="button" title="Grille"><i class="bi bi-grid"></i></button>
                    <button class="view-btn" id="list-view" type="button" title="Liste"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </div>

        <div class="courses-grid" id="courses-grid">
            @forelse($courses as $courseIndex => $course)
                @php
                $gradients = [
                    'linear-gradient(135deg,#1e1b4b,#3730a3)',
                    'linear-gradient(135deg,#0c4a6e,#0284c7)',
                    'linear-gradient(135deg,#14532d,#16a34a)',
                    'linear-gradient(135deg,#7c2d12,#ea580c)',
                    'linear-gradient(135deg,#4a1d96,#7c3aed)',
                    'linear-gradient(135deg,#1e3a5f,#0891b2)',
                ];
                $cardIcons = [
                    'bi-code-slash','bi-lightning','bi-palette','bi-phone',
                    'bi-shield-lock','bi-cpu','bi-cloud','bi-bar-chart',
                    'bi-globe','bi-bullseye','bi-rocket','bi-lightbulb',
                ];
                $courseGrad    = $gradients[$course->id % count($gradients)];
                $cardIcon      = $cardIcons[$course->id % count($cardIcons)];
                $avgRating     = $course->reviews_avg_rating ?? 0;
                $studentsCount = $course->students_count ?? 0;
                $reviewsCount  = $course->reviews_count ?? 0;
                $isFree        = ($course->price ?? 0) == 0;
                $niveaux       = ['debutant' => 'Debutant','intermediaire' => 'Intermediaire','avance' => 'Avance'];
                $niveau        = $course->level ?? 'debutant';
                $niveauLabel   = $niveaux[$niveau] ?? 'Debutant';
                $isNew         = $course->created_at->diffInDays() < 14;
                $lessonsCount  = $course->lessons_count ?? 0;
                $authorName    = optional($course->formateur)->name ?? '';
                $authorInits   = $authorName ? strtoupper(substr($authorName, 0, 2)) : '';
                $fullStars     = $avgRating > 0 ? min(5, (int) round($avgRating)) : 0;
                $starsHtml     = str_repeat('★', $fullStars) . str_repeat('☆', 5 - $fullStars);
                @endphp

                <article class="course-card rv"
                    style="transition-delay:{{ ($courseIndex % 8) * 55 }}ms"
                    onclick="window.location='{{ route('courses.show', $course) }}'"
                    role="article">

                    <div class="card-thumb">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                 alt="{{ $course->title }}"
                                 class="card-thumb-img"
                                 loading="lazy">
                        @else
                            <div class="card-thumb-ico" style="background:{{ $courseGrad }}">
                                <i class="bi {{ $cardIcon }}" style="color:rgba(255,255,255,.7)"></i>
                            </div>
                        @endif

                        <div class="card-badges">
                            @if($course->category)
                                <span class="card-badge cb-cat">{{ Str::limit($course->category->name, 12) }}</span>
                            @endif
                            @if($isNew)
                                <span class="card-badge cb-new">Nouveau</span>
                            @elseif($studentsCount > 100)
                                <span class="card-badge cb-pop">Populaire</span>
                            @endif
                            @if($isFree)
                                <span class="card-badge cb-free">Gratuit</span>
                            @endif
                        </div>

                        @if(!$isFree)
                            <div class="card-price-badge">{{ number_format($course->price, 0, ',', ' ') }} EUR</div>
                        @endif

                        <div class="card-action-overlay">
                            <button class="card-quick-btn" type="button"
                                    onclick="event.stopPropagation();window.location='{{ route('courses.show', $course) }}'">
                                <i class="bi bi-play-circle-fill"></i> Voir le cours
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-level lvl-{{ $niveau }}">
                            <i class="bi bi-bar-chart-fill"></i> {{ $niveauLabel }}
                        </div>
                        <h3 class="card-title">{{ $course->title }}</h3>
                        @if($authorName)
                            <div class="card-author">
                                <div class="card-av">{{ $authorInits }}</div>
                                <span>{{ $authorName }}</span>
                            </div>
                        @endif
                        @if($avgRating > 0)
                            <div class="card-rating">
                                <div class="rating-stars">{{ $starsHtml }}</div>
                                <span class="rating-val">{{ number_format($avgRating, 1) }}</span>
                                <span class="rating-count">({{ $reviewsCount }})</span>
                            </div>
                        @endif
                        @if($lessonsCount > 0)
                            <div class="card-meta">
                                <i class="bi bi-collection-play" style="font-size:.76rem"></i>
                                {{ $lessonsCount }} lecon{{ $lessonsCount > 1 ? 's' : '' }}
                            </div>
                        @endif
                    </div>

                    <div class="card-foot">
                        <div>
                            @if($isFree)
                                <span class="card-price-free">Gratuit</span>
                            @else
                                <span class="card-price">{{ number_format($course->price, 0, ',', ' ') }} EUR</span>
                            @endif
                        </div>
                        <div class="card-students">
                            <i class="bi bi-people"></i> {{ number_format($studentsCount) }}
                        </div>
                    </div>

                </article>

            @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-search"></i></div>
                    <h3 class="empty-title">Aucun cours trouve</h3>
                    <p class="empty-desc">
                        @if(request('search'))
                            Aucun resultat pour "{{ request('search') }}". Essayez d'autres mots-cles.
                        @else
                            Aucun cours ne correspond a vos criteres.
                        @endif
                    </p>
                    <a href="{{ route('courses.index') }}" class="btn-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> Voir tous les cours
                    </a>
                </div>
            @endforelse
        </div>

        @if($courses->hasPages())
            <div class="pag-wrap rv">{{ $courses->appends(request()->query())->links() }}</div>
        @endif

    </div>
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <a href="/" class="f-logo">
                <div class="nav-logo-ic" style="width:32px;height:32px;font-size:.9rem"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
                <span style="font-size:1.05rem;font-weight:800;color:#fff">LearnHub</span>
            </a>
            <p class="f-desc">La plateforme d'apprentissage qui propulse votre carriere.</p>
            <div class="f-socials">
                <a href="#" class="f-soc"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="f-soc"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="f-soc"><i class="bi bi-instagram"></i></a>
                <a href="#" class="f-soc"><i class="bi bi-youtube"></i></a>
                <a href="#" class="f-soc"><i class="bi bi-github"></i></a>
            </div>
        </div>
        <div>
            <div class="f-col-ttl">Plateforme</div>
            <ul class="f-links">
                <li><a href="#" class="f-link">Catalogue</a></li>
                <li><a href="#" class="f-link">Formateurs</a></li>
                <li><a href="#" class="f-link">Certifications</a></li>
                <li><a href="#" class="f-link">Blog</a></li>
                <li><a href="#" class="f-link">Tarifs</a></li>
            </ul>
        </div>
        <div>
            <div class="f-col-ttl">Entreprise</div>
            <ul class="f-links">
                <li><a href="#" class="f-link">A propos</a></li>
                <li><a href="#" class="f-link">Carrieres</a></li>
                <li><a href="#" class="f-link">Presse</a></li>
                <li><a href="#" class="f-link">Partenaires</a></li>
                <li><a href="#" class="f-link">Contact</a></li>
            </ul>
        </div>
        <div>
            <div class="f-col-ttl">Legal</div>
            <ul class="f-links">
                <li><a href="#" class="f-link">Conditions</a></li>
                <li><a href="#" class="f-link">Confidentialite</a></li>
                <li><a href="#" class="f-link">Cookies</a></li>
                <li><a href="#" class="f-link">Accessibilite</a></li>
            </ul>
        </div>
    </div>
    <div class="f-bottom">
        <div class="f-copy">Copyright {{ date('Y') }} LearnHub. Tous droits reserves.</div>
        <div class="f-dev">
            <i class="bi bi-code-slash" style="color:#8b5cf6"></i>
            Designed and Developed by <strong>Pape Seck</strong>
        </div>
        <div style="display:flex;gap:1.1rem">
            <a href="#" class="f-link">CGU</a>
            <a href="#" class="f-link">Confidentialite</a>
        </div>
    </div>
</footer>

<script>
(function () {
    'use strict';

    /* ── NAVBAR: hide on scroll down, reveal on scroll up ── */
    var navbar = document.getElementById('navbar');
    var lastY = 0;
    var ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(function () {
                var y = window.scrollY;
                if (y > lastY && y > 80) {
                    navbar.classList.add('hidden');
                } else {
                    navbar.classList.remove('hidden');
                }
                if (y > 10) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                lastY = y;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    /* ── MOBILE DRAWER ── */
    var burger = document.getElementById('burger');
    var ov = document.getElementById('mob-ov');
    var drawer = document.getElementById('mob-drawer');
    var closeBtn = document.getElementById('mob-close');
    var menuOpen = false;

    function openMenu() {
        menuOpen = true;
        burger.classList.add('on');
        ov.classList.add('on');
        drawer.classList.add('on');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        menuOpen = false;
        burger.classList.remove('on');
        ov.classList.remove('on');
        drawer.classList.remove('on');
        document.body.style.overflow = '';
    }

    burger.addEventListener('click', function (e) {
        e.stopPropagation();
        if (menuOpen) { closeMenu(); } else { openMenu(); }
    });
    closeBtn.addEventListener('click', closeMenu);
    ov.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && menuOpen) { closeMenu(); }
    });

    /* ── SCROLL REVEAL ── */
    var rvEls = document.querySelectorAll('.rv');
    if (rvEls.length) {
        var rvObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                    rvObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });
        rvEls.forEach(function (el) { rvObs.observe(el); });
    }

    /* ── COUNT-UP ANIMATION ── */
    function countUp(el, target, dur) {
        var start = performance.now();
        var isLarge = target >= 1000;
        (function run(now) {
            var p = Math.min((now - start) / dur, 1);
            var val = Math.round((1 - Math.pow(1 - p, 3)) * target);
            if (isLarge) {
                el.textContent = val >= 1000 ? (val / 1000).toFixed(val % 1000 === 0 ? 0 : 1) + 'k+' : val + '+';
            } else {
                el.textContent = val + '+';
            }
            if (p < 1) { requestAnimationFrame(run); }
        })(start);
    }

    var cuObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting && e.target.dataset.count) {
                countUp(e.target, +e.target.dataset.count, 1400);
                cuObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.3 });
    document.querySelectorAll('[data-count]').forEach(function (el) { cuObs.observe(el); });

    /* ── GRID / LIST VIEW TOGGLE ── */
    var grid = document.getElementById('courses-grid');
    var btnG = document.getElementById('grid-view');
    var btnL = document.getElementById('list-view');
    if (grid && btnG && btnL) {
        btnG.addEventListener('click', function () {
            grid.style.gridTemplateColumns = '';
            btnG.classList.add('active');
            btnL.classList.remove('active');
        });
        btnL.addEventListener('click', function () {
            grid.style.gridTemplateColumns = '1fr';
            btnL.classList.add('active');
            btnG.classList.remove('active');
        });
    }

    /* ── TRENDING CAROUSEL ── */
    var track = document.getElementById('ts-track');
    var prevBtn = document.getElementById('ts-prev');
    var nextBtn = document.getElementById('ts-next');
    var dotsC = document.getElementById('ts-dots');

    if (track && prevBtn && nextBtn && dotsC) {
        var cur = 0;
        var autoT = null;

        function perView() {
            var w = track.parentElement.offsetWidth;
            if (w < 520) { return 1; }
            if (w < 900) { return 2; }
            return 4;
        }

        function pages() {
            return Math.max(1, track.children.length - perView() + 1);
        }

        function buildDots() {
            dotsC.innerHTML = '';
            var total = pages();
            for (var i = 0; i < total; i++) {
                (function (idx) {
                    var d = document.createElement('div');
                    d.className = 'ts-dot' + (idx === cur ? ' on' : '');
                    d.addEventListener('click', function () { go(idx); resetAuto(); });
                    dotsC.appendChild(d);
                })(i);
            }
        }

        function go(idx) {
            var total = pages();
            cur = Math.max(0, Math.min(idx, total - 1));
            var pv = perView();
            var gap = 17;
            var totW = track.parentElement.offsetWidth;
            var cw = (totW - gap * (pv - 1)) / pv;
            track.style.transform = 'translateX(-' + (cur * (cw + gap)) + 'px)';
            dotsC.querySelectorAll('.ts-dot').forEach(function (d, i) {
                d.classList.toggle('on', i === cur);
            });
        }

        prevBtn.addEventListener('click', function () { go(cur - 1); resetAuto(); });
        nextBtn.addEventListener('click', function () {
            go(cur + 1 >= pages() ? 0 : cur + 1);
            resetAuto();
        });

        var tx0 = 0;
        track.addEventListener('touchstart', function (e) {
            tx0 = e.touches[0].clientX;
        }, { passive: true });
        track.addEventListener('touchend', function (e) {
            var dx = tx0 - e.changedTouches[0].clientX;
            if (Math.abs(dx) > 40) {
                go(dx > 0 ? (cur + 1 >= pages() ? 0 : cur + 1) : cur - 1);
                resetAuto();
            }
        }, { passive: true });

        function resetAuto() {
            clearInterval(autoT);
            autoT = setInterval(function () {
                go(cur + 1 >= pages() ? 0 : cur + 1);
            }, 4500);
        }

        buildDots();
        go(0);
        resetAuto();
        window.addEventListener('resize', function () { buildDots(); go(cur); });
    }

    /* ── CATEGORY FILTER: preserve search when clicking category pills ── */
    var catPills = document.querySelectorAll('.cat-pill');
    catPills.forEach(function (pill) {
        pill.addEventListener('click', function (e) {
            e.preventDefault();
            var href = pill.getAttribute('href');
            window.location.href = href;
        });
    });

})();
</script>
</body>
</html>