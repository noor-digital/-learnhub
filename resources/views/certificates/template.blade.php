<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    width: 297mm; height: 210mm;
    font-family: 'Inter', Arial, sans-serif;
    background: #fff;
    overflow: hidden;
}

.page {
    width: 297mm; height: 210mm;
    position: relative;
    background: #fff;
    padding: 0;
}

/* ── Fond décoratif ────────────────────────── */
.bg-gradient {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%;
    background:
        radial-gradient(ellipse at 5% 10%, rgba(108,99,255,.06) 0%, transparent 40%),
        radial-gradient(ellipse at 95% 90%, rgba(108,99,255,.06) 0%, transparent 40%);
}

/* ── Bande latérale gauche ─────────────────── */
.left-band {
    position: absolute; top: 0; left: 0;
    width: 18mm; height: 100%;
    background: linear-gradient(180deg, #1a1a2e 0%, #6c63ff 100%);
}

/* ── Bande latérale droite ─────────────────── */
.right-band {
    position: absolute; top: 0; right: 0;
    width: 6mm; height: 100%;
    background: #6c63ff;
    opacity: .3;
}

/* ── Bordure décorative ────────────────────── */
.border-deco {
    position: absolute;
    top: 8mm; left: 24mm; right: 12mm; bottom: 8mm;
    border: 1px solid rgba(108,99,255,.2);
}
.border-deco-inner {
    position: absolute;
    top: 11mm; left: 27mm; right: 14mm; bottom: 11mm;
    border: 0.3px solid rgba(108,99,255,.12);
}

/* ── Contenu principal ─────────────────────── */
.content {
    position: absolute;
    top: 0; left: 20mm; right: 8mm; bottom: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 12mm 20mm 10mm;
    text-align: center;
}

/* ── Logo/Brand ────────────────────────────── */
.brand {
    display: flex; align-items: center;
    justify-content: center;
    gap: 8px; margin-bottom: 5mm;
}
.brand-dot {
    width: 9mm; height: 9mm; border-radius: 2.5mm;
    background: #6c63ff;
    display: flex; align-items: center; justify-content: center;
}
.brand-name {
    font-size: 14pt; font-weight: 700;
    color: #1a1a2e; letter-spacing: .3px;
}

/* ── Titre certificat ──────────────────────── */
.cert-main-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 26pt; color: #1a1a2e;
    letter-spacing: .5px; margin-bottom: 3mm;
    font-weight: 700;
}
.cert-subtitle {
    font-size: 8.5pt; letter-spacing: 3px;
    text-transform: uppercase; color: #a8a5b8;
    margin-bottom: 5mm;
}

/* ── Nom du récipiendaire ──────────────────── */
.recipient-label {
    font-size: 8pt; color: #6b6882; margin-bottom: 2mm;
    text-transform: uppercase; letter-spacing: 1.5px;
}
.recipient-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 28pt; color: #6c63ff; font-weight: 700;
    letter-spacing: .3px; margin-bottom: 3mm;
    border-bottom: 1.5px solid rgba(108,99,255,.25);
    padding-bottom: 2mm; display: inline-block;
    min-width: 100mm;
}

/* ── Texte principal ───────────────────────── */
.cert-body {
    font-size: 10pt; color: #6b6882;
    line-height: 1.7; margin-bottom: 3mm;
}
.course-name {
    font-size: 12pt; font-weight: 700;
    color: #1a1a2e; font-style: italic;
}

/* ── Footer ────────────────────────────────── */
.footer-row {
    display: flex; justify-content: space-between;
    align-items: flex-end; width: 100%;
    margin-top: 6mm; padding: 0 4mm;
}

.sign-block { text-align: center; min-width: 45mm; }
.sign-name  { font-size: 9pt; font-weight: 600; color: #1a1a2e; }
.sign-title { font-size: 7.5pt; color: #a8a5b8; text-transform: uppercase; letter-spacing: .5px; }
.sign-line  { width: 40mm; border-bottom: 0.8px solid #d3d1c7; margin: 0 auto 1.5mm; }

.cert-center { text-align: center; }
.seal {
    font-size: 26pt; display: block; margin-bottom: 1mm;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
}
.cert-date {
    font-size: 8.5pt; color: #1a1a2e;
    font-weight: 600; margin-bottom: 1mm;
}
.cert-id {
    font-size: 7pt; color: #a8a5b8;
    font-family: 'Courier New', monospace;
    letter-spacing: .5px;
}

/* ── Coins décoratifs ──────────────────────── */
.corner {
    position: absolute; width: 12mm; height: 12mm;
}
.c-tl { top: 14mm; left: 26mm; border-top: 2px solid #6c63ff; border-left: 2px solid #6c63ff; }
.c-tr { top: 14mm; right: 13mm; border-top: 2px solid #6c63ff; border-right: 2px solid #6c63ff; }
.c-bl { bottom: 14mm; left: 26mm; border-bottom: 2px solid #6c63ff; border-left: 2px solid #6c63ff; }
.c-br { bottom: 14mm; right: 13mm; border-bottom: 2px solid #6c63ff; border-right: 2px solid #6c63ff; }
</style>
</head>
<body>
<div class="page">

    <div class="bg-gradient"></div>
    <div class="left-band"></div>
    <div class="right-band"></div>
    <div class="border-deco"></div>
    <div class="border-deco-inner"></div>

    <!-- Coins -->
    <div class="corner c-tl"></div>
    <div class="corner c-tr"></div>
    <div class="corner c-bl"></div>
    <div class="corner c-br"></div>

    <div class="content">

        <!-- Brand -->
        <div class="brand">
            <div class="brand-dot">
                <span style="color:#fff;font-size:5.5pt;font-weight:700">LH</span>
            </div>
            <div class="brand-name">LearnHub</div>
        </div>

        <!-- Titre -->
        <div class="cert-main-title">Certificat de Réussite</div>
        <div class="cert-subtitle">
            Excellence · Formation · Accomplissement
        </div>

        <!-- Récipiendaire -->
        <div class="recipient-label">Décerné à</div>
        <div class="recipient-name">{{ $user->name }}</div>

        <!-- Corps -->
        <div class="cert-body">
            pour avoir suivi et réussi avec succès la formation
        </div>
        <div class="course-name">"{{ $course->title }}"</div>
        <div class="cert-body" style="margin-top:2mm;font-size:8.5pt">
            dispensée par <strong style="color:#1a1a2e">{{ $course->formateur->name }}</strong>
            &nbsp;·&nbsp;
            Catégorie : <strong style="color:#1a1a2e">{{ $course->category->name }}</strong>
        </div>

        <!-- Footer -->
        <div class="footer-row">

            <div class="sign-block">
                <div class="sign-line"></div>
                <div class="sign-name">{{ $course->formateur->name }}</div>
                <div class="sign-title">Formateur</div>
            </div>

            <div class="cert-center">
                <span class="seal">🏆</span>
                <div class="cert-date">{{ $date }}</div>
                <div class="cert-id">
                    Réf. #{{ $certificate_id }}
                </div>
            </div>

            <div class="sign-block">
                <div class="sign-line"></div>
                <div class="sign-name">LearnHub Platform</div>
                <div class="sign-title">Direction pédagogique</div>
            </div>

        </div>
    </div>
</div>
</body>
</html>