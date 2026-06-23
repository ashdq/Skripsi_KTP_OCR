<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelurahan Digital - Sistem Informasi Kelurahan</title>
    <meta name="description" content="Portal Digital Administrasi Kelurahan - Akses Layanan Warga Mudah & Cepat. Pengajuan surat keterangan, pendaftaran penduduk, dan cek status dokumen online.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:   #1a3d2b;
            --green-main:   #1d4d35;
            --green-mid:    #2e6b4a;
            --green-light:  #3a8a5e;
            --green-accent: #4caf78;
            --white:        #ffffff;
            --off-white:    #f5f7f5;
            --text-dark:    #1c2b22;
            --text-mid:     #4a6358;
            --text-light:   #7a9a8a;
            --border:       #d4e4da;
            --shadow-sm:    0 2px 8px rgba(0,0,0,.08);
            --shadow-md:    0 8px 32px rgba(0,0,0,.12);
            --shadow-lg:    0 20px 60px rgba(0,0,0,.18);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ─── NAVBAR ─────────────────────────────── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(26,61,43,.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,.08);
            transition: background .3s;
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem;
        }

        .nav-brand-text {
            display: flex; flex-direction: column;
        }

        .nav-brand-sub {
            font-size: .65rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(255,255,255,.55);
        }

        .nav-brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: white;
            letter-spacing: -.01em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            transition: color .2s;
        }

        .nav-links a:hover { color: white; }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-outline {
            padding: .5rem 1.25rem;
            border-radius: 50px;
            border: 1.5px solid rgba(255,255,255,.3);
            background: transparent;
            color: white;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-outline:hover {
            border-color: white;
            background: rgba(255,255,255,.1);
        }

        .btn-solid {
            padding: .5rem 1.4rem;
            border-radius: 50px;
            border: none;
            background: var(--green-accent);
            color: white;
            font-size: .875rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-solid:hover {
            background: #5bc98a;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(76,175,120,.4);
        }

        /* ─── HERO ────────────────────────────────── */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-main) 55%, #164430 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding-top: 68px;
        }

        /* Decorative blobs */
        .hero::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(76,175,120,.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(255,255,255,.04) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 2.5rem 2rem;
            display: grid;
            grid-template-columns: 1.1fr 1.3fr;
            gap: 3rem;
            align-items: center;
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: calc(100vh - 68px);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50px;
            padding: .4rem 1rem;
            color: rgba(255,255,255,.9);
            font-size: .8rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(8px);
        }

        .hero-badge i { font-size: .7rem; color: var(--green-accent); }

        .hero-title {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 800;
            color: white;
            line-height: 1.15;
            letter-spacing: -.03em;
            margin-bottom: 1.25rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,.7);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 480px;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .85rem 1.8rem;
            border-radius: 50px;
            background: var(--green-accent);
            color: white;
            font-size: .95rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .25s;
            box-shadow: 0 4px 20px rgba(76,175,120,.4);
        }

        .btn-hero-primary:hover {
            background: #5bc98a;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(76,175,120,.5);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .85rem 1.8rem;
            border-radius: 50px;
            border: 1.5px solid rgba(255,255,255,.3);
            background: rgba(255,255,255,.08);
            color: white;
            font-size: .95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .25s;
            backdrop-filter: blur(8px);
        }

        .btn-hero-secondary:hover {
            border-color: rgba(255,255,255,.6);
            background: rgba(255,255,255,.15);
            transform: translateY(-2px);
        }

        /* ─── HERO ILLUSTRATION ──────────────────── */
        .hero-visual {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            align-items: stretch;
            height: 100%;
        }

        .hero-building-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-top: 1rem;
        }

        /* SVG Kelurahan Building Illustration */
        .building-scene {
            width: 100%;
            height: auto;
            min-height: 380px;
        }

        /* ─── FLOATING CARD ──────────────────────── */
        .floating-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 24px 60px rgba(0,0,0,.22);
            display: flex;
            flex-direction: column;
            gap: .9rem;
            align-self: center;
            animation: float 5s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .floating-card-title {
            font-size: .8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .floating-card-features {
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .fc-feature {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .fc-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            flex-shrink: 0;
        }

        .fc-icon.orange { background: #fff4e6; color: #f97316; }
        .fc-icon.blue { background: #e6f0ff; color: #3b82f6; }
        .fc-icon.green { background: #e6f7ee; color: #22c55e; }

        .fc-feature-name { font-size: .82rem; font-weight: 700; color: var(--text-dark); }
        .fc-feature-sub { font-size: .73rem; color: var(--text-light); }

        /* Testimonial mini card */
        .mini-testimonial {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-md);
            margin-top: 1rem;
            font-size: .8rem;
            color: var(--text-mid);
            font-style: italic;
            line-height: 1.5;
        }

        .mini-testimonial strong { color: var(--green-dark); font-style: normal; }
        .mini-testimonial .t-source { font-size: .72rem; color: var(--green-accent); font-style: normal; font-weight: 600; margin-top: .5rem; }

        /* ─── SERVICES SECTION ───────────────────── */
        .services {
            background: var(--off-white);
            padding: 5rem 2rem;
            position: relative;
            margin-top: -60px;
            z-index: 2;
        }

        .services-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-label {
            display: inline-block;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--green-accent);
            margin-bottom: .75rem;
        }

        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -.02em;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--text-mid);
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 3rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all .3s ease;
            cursor: default;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green-accent), var(--green-mid));
            transform: scaleX(0);
            transition: transform .3s;
        }

        .service-card:hover::before { transform: scaleX(1); }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .service-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
        }

        .service-icon.orange { background: linear-gradient(135deg, #fff4e6, #fde8c8); color: #f97316; }
        .service-icon.green  { background: linear-gradient(135deg, #e8f5ee, #c8ebd8); color: #22c55e; }
        .service-icon.blue   { background: linear-gradient(135deg, #e8f0fe, #c8d8fc); color: #3b82f6; }

        .service-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: -.01em;
            margin-bottom: .75rem;
            line-height: 1.3;
        }

        .service-desc {
            font-size: .875rem;
            color: var(--text-mid);
            line-height: 1.65;
        }

        /* ─── WHY US SECTION ─────────────────────── */
        .why-us {
            padding: 5rem 2rem;
            background: white;
        }

        .why-us-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .why-us-content { }

        .why-features {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .why-feature {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .why-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .why-icon.orange { background: #fff4e6; color: #f97316; }
        .why-icon.teal   { background: #e6f9f5; color: #0d9488; }
        .why-icon.blue   { background: #e6f0ff; color: #3b82f6; }

        .why-feature-name { font-size: .95rem; font-weight: 700; color: var(--text-dark); margin-bottom: .25rem; }
        .why-feature-sub  { font-size: .85rem; color: var(--text-mid); }

        /* Card column */
        .why-us-cards {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .info-card.highlight {
            background: var(--green-main);
            border-color: transparent;
        }

        .info-card.highlight .info-title { color: white; }
        .info-card.highlight .info-body  { color: rgba(255,255,255,.75); }
        .info-card.highlight .info-source { color: var(--green-accent); }

        .info-title { font-size: .875rem; font-weight: 700; color: var(--text-dark); margin-bottom: .75rem; }
        .info-body  { font-size: .85rem; color: var(--text-mid); line-height: 1.6; font-style: italic; }
        .info-source { font-size: .8rem; color: var(--green-accent); font-weight: 700; margin-top: .5rem; }

        .why-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.25rem 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--off-white);
        }

        .stat-number {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--green-main);
            letter-spacing: -.03em;
        }

        .stat-label {
            font-size: .75rem;
            color: var(--text-mid);
            margin-top: .25rem;
            line-height: 1.4;
        }

        /* ─── FOOTER ─────────────────────────────── */
        footer {
            background: var(--green-dark);
            color: rgba(255,255,255,.8);
            padding: 4rem 2rem 2rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 2rem;
        }

        .footer-brand { }

        .footer-brand-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: .5rem;
        }

        .footer-brand-desc {
            font-size: .85rem;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            margin-bottom: 1.25rem;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .82rem;
            color: rgba(255,255,255,.6);
            margin-bottom: .5rem;
        }

        .footer-contact-item i { color: var(--green-accent); width: 14px; }

        .footer-col-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: white;
            margin-bottom: 1.25rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .footer-links a {
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .85rem;
            transition: color .2s;
        }

        .footer-links a:hover { color: var(--green-accent); }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: rgba(255,255,255,.35);
        }

        /* ─── SCROLL ANIMATIONS ──────────────────── */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-up.delay-1 { transition-delay: .1s; }
        .fade-up.delay-2 { transition-delay: .2s; }
        .fade-up.delay-3 { transition-delay: .3s; }

        /* ─── RESPONSIVE ─────────────────────────── */
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 2rem; }
            .hero-visual { display: none; }
            .hero-cta { justify-content: center; }
            .services-grid { grid-template-columns: 1fr; }
            .why-us-inner { grid-template-columns: 1fr; gap: 3rem; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .nav-links { display: none; }
        }

        @media (max-width: 600px) {
            .footer-grid { grid-template-columns: 1fr; }
            .why-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ─── NAVBAR ─────────────────────────────────── -->
<nav class="navbar">
    <div class="nav-inner">
        <a href="/" class="nav-brand">
            <div class="nav-brand-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="nav-brand-text">
                <span class="nav-brand-sub">Sistem Informasi Kelurahan</span>
                <span class="nav-brand-name">Kelurahan Digital</span>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#layanan">Layanan</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>

        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-outline">Login</a>
            <a href="{{ route('register') }}" class="btn-solid">Daftar</a>
        </div>
    </div>
</nav>

<!-- ─── HERO ─────────────────────────────────────── -->
<section class="hero">
    <div class="hero-inner">
        <!-- Left: Copy -->
        <div class="hero-content">
            <span class="hero-badge">
                <i class="fas fa-circle"></i>
                Akses layanan warga dalam satu pintu
            </span>

            <h1 class="hero-title">
                Pelayanan surat<br>jadi lebih cepat<br>dan rapi
            </h1>

            <p class="hero-subtitle">
                Portal Digital Administrasi Kelurahan – Akses Layanan Warga Mudah &amp; Cepat
            </p>

            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn-hero-primary" id="btn-masuk">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" class="btn-hero-secondary" id="btn-buat-akun">
                    <i class="fas fa-user-plus"></i> Buat Akun
                </a>
            </div>
        </div>

        <!-- Right: Illustration + Floating Card side by side -->
        <div class="hero-visual">
            <!-- Left col: Building SVG -->
            <div class="hero-building-wrap">
                <svg class="building-scene" viewBox="0 0 380 480" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Sky gradient overlay -->
                    <defs>
                        <radialGradient id="glow" cx="50%" cy="40%" r="50%">
                            <stop offset="0%" stop-color="rgba(76,175,120,.18)"/>
                            <stop offset="100%" stop-color="transparent"/>
                        </radialGradient>
                    </defs>
                    <ellipse cx="190" cy="180" rx="180" ry="160" fill="url(#glow)"/>

                    <!-- Ground -->
                    <rect x="0" y="420" width="380" height="60" fill="rgba(255,255,255,.06)"/>
                    <!-- Ground line -->
                    <line x1="0" y1="420" x2="380" y2="420" stroke="rgba(255,255,255,.15)" stroke-width="1"/>

                    <!-- Left tree -->
                    <ellipse cx="32" cy="340" rx="28" ry="44" fill="rgba(76,175,120,.28)"/>
                    <ellipse cx="32" cy="315" rx="22" ry="36" fill="rgba(76,175,120,.2)"/>
                    <rect x="26" y="375" width="12" height="48" rx="4" fill="rgba(76,175,120,.2)"/>
                    <!-- Left tree 2 -->
                    <ellipse cx="62" cy="355" rx="20" ry="32" fill="rgba(76,175,120,.18)"/>
                    <rect x="57" y="382" width="10" height="40" rx="3" fill="rgba(76,175,120,.15)"/>

                    <!-- Right tree -->
                    <ellipse cx="348" cy="335" rx="26" ry="42" fill="rgba(76,175,120,.25)"/>
                    <ellipse cx="348" cy="312" rx="20" ry="32" fill="rgba(76,175,120,.18)"/>
                    <rect x="342" y="370" width="12" height="52" rx="4" fill="rgba(76,175,120,.18)"/>

                    <!-- Building main body -->
                    <rect x="75" y="195" width="230" height="230" rx="6" fill="rgba(255,255,255,.1)"/>
                    <rect x="75" y="195" width="230" height="230" rx="6" stroke="rgba(255,255,255,.22)" stroke-width="2"/>

                    <!-- Roof triangle -->
                    <polygon points="55,200 190,85 325,200" fill="rgba(255,255,255,.16)"/>
                    <polygon points="55,200 190,85 325,200" stroke="rgba(255,255,255,.28)" stroke-width="2" fill="none"/>

                    <!-- Roof ridge detail -->
                    <line x1="55" y1="200" x2="325" y2="200" stroke="rgba(255,255,255,.2)" stroke-width="1.5"/>

                    <!-- Flag pole -->
                    <line x1="190" y1="84" x2="190" y2="48" stroke="rgba(255,255,255,.55)" stroke-width="2.5"/>
                    <!-- Flag -->
                    <rect x="190" y="48" width="34" height="20" rx="3" fill="rgba(76,175,120,.75)"/>
                    <line x1="207" y1="53" x2="207" y2="63" stroke="rgba(255,255,255,.4)" stroke-width="1"/>

                    <!-- KELURAHAN sign -->
                    <rect x="110" y="210" width="160" height="32" rx="5" fill="rgba(76,175,120,.45)"/>
                    <rect x="110" y="210" width="160" height="32" rx="5" stroke="rgba(255,255,255,.2)" stroke-width="1"/>
                    <text x="190" y="231" text-anchor="middle" font-family="Arial" font-weight="800" font-size="11" fill="white" letter-spacing="2.5">KELURAHAN</text>

                    <!-- Windows row -->
                    <rect x="92"  y="260" width="44" height="38" rx="4" fill="rgba(255,255,255,.13)" stroke="rgba(255,255,255,.22)" stroke-width="1"/>
                    <rect x="148" y="260" width="44" height="38" rx="4" fill="rgba(255,255,255,.18)" stroke="rgba(255,255,255,.22)" stroke-width="1"/>
                    <rect x="204" y="260" width="44" height="38" rx="4" fill="rgba(255,255,255,.13)" stroke="rgba(255,255,255,.22)" stroke-width="1"/>
                    <rect x="260" y="260" width="44" height="38" rx="4" fill="rgba(255,255,255,.18)" stroke="rgba(255,255,255,.22)" stroke-width="1"/>

                    <!-- Window cross lines -->
                    <line x1="114" y1="260" x2="114" y2="298" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="92"  y1="279" x2="136" y2="279" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="170" y1="260" x2="170" y2="298" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="148" y1="279" x2="192" y2="279" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="226" y1="260" x2="226" y2="298" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="204" y1="279" x2="248" y2="279" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="282" y1="260" x2="282" y2="298" stroke="rgba(255,255,255,.18)" stroke-width="1"/>
                    <line x1="260" y1="279" x2="304" y2="279" stroke="rgba(255,255,255,.18)" stroke-width="1"/>

                    <!-- Door -->
                    <rect x="155" y="328" width="70" height="97" rx="7" fill="rgba(76,175,120,.35)" stroke="rgba(255,255,255,.22)" stroke-width="1.5"/>
                    <rect x="162" y="336" width="27" height="89" rx="3" fill="rgba(255,255,255,.08)"/>
                    <rect x="191" y="336" width="27" height="89" rx="3" fill="rgba(255,255,255,.08)"/>
                    <circle cx="188" cy="378" r="3.5" fill="rgba(255,255,255,.65)"/>
                    <circle cx="193" cy="378" r="3.5" fill="rgba(255,255,255,.65)"/>
                    <!-- Door arch top -->
                    <path d="M155,345 Q190,315 225,345" fill="rgba(76,175,120,.25)" stroke="rgba(255,255,255,.2)" stroke-width="1"/>

                    <!-- Steps -->
                    <rect x="130" y="420" width="120" height="10" rx="2" fill="rgba(255,255,255,.18)"/>
                    <rect x="142" y="412" width="96" height="10" rx="2" fill="rgba(255,255,255,.13)"/>
                    <rect x="152" y="404" width="76" height="10" rx="2" fill="rgba(255,255,255,.10)"/>

                    <!-- Columns/pillars -->
                    <rect x="105" y="328" width="16" height="97" rx="3" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.15)" stroke-width="1"/>
                    <rect x="259" y="328" width="16" height="97" rx="3" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.15)" stroke-width="1"/>

                    <!-- Person 1 - woman with tablet, left -->
                    <circle cx="55" cy="365" r="13" fill="rgba(255,255,255,.28)"/>
                    <rect x="47" y="378" width="16" height="45" rx="8" fill="rgba(255,255,255,.22)"/>
                    <rect x="36" y="382" width="14" height="7" rx="3.5" fill="rgba(255,255,255,.18)"/>
                    <rect x="63" y="380" width="12" height="16" rx="3" fill="rgba(255,255,255,.38)"/>

                    <!-- Person 2 - man, right -->
                    <circle cx="325" cy="362" r="13" fill="rgba(76,175,120,.45)"/>
                    <rect x="317" y="375" width="16" height="47" rx="8" fill="rgba(76,175,120,.35)"/>
                    <rect x="308" y="382" width="12" height="7" rx="3.5" fill="rgba(76,175,120,.28)"/>
                    <rect x="333" y="382" width="12" height="7" rx="3.5" fill="rgba(76,175,120,.28)"/>

                    <!-- Person 3 - near building left -->
                    <circle cx="105" cy="375" r="11" fill="rgba(255,255,255,.22)"/>
                    <rect x="98" y="386" width="14" height="37" rx="7" fill="rgba(255,255,255,.18)"/>
                    <!-- clipboard -->
                    <rect x="111" y="384" width="11" height="14" rx="2" fill="rgba(255,255,255,.4)"/>

                    <!-- Person 4 - near building right -->
                    <circle cx="275" cy="372" r="11" fill="rgba(76,175,120,.4)"/>
                    <rect x="268" y="383" width="14" height="40" rx="7" fill="rgba(76,175,120,.3)"/>
                    <rect x="282" y="385" width="9" height="12" rx="2" fill="rgba(255,255,255,.3)"/>

                    <!-- Decorative dots -->
                    <circle cx="20" cy="200" r="2" fill="rgba(255,255,255,.2)"/>
                    <circle cx="10" cy="240" r="1.5" fill="rgba(255,255,255,.15)"/>
                    <circle cx="360" cy="190" r="2" fill="rgba(255,255,255,.2)"/>
                    <circle cx="370" cy="230" r="1.5" fill="rgba(255,255,255,.15)"/>
                </svg>
            </div>

            <!-- Right col: Why Choose Us card -->
            <div class="floating-card">
                <div class="floating-card-title">Why Choose Us</div>
                <div class="floating-card-features">
                    <div class="fc-feature">
                        <div class="fc-icon orange"><i class="fas fa-bolt"></i></div>
                        <div>
                            <div class="fc-feature-name">Efisien</div>
                            <div class="fc-feature-sub">(Hemat Waktu)</div>
                        </div>
                    </div>
                    <div class="fc-feature">
                        <div class="fc-icon blue"><i class="fas fa-file-alt"></i></div>
                        <div>
                            <div class="fc-feature-name">Transparan</div>
                            <div class="fc-feature-sub">(Proses Terbuka)</div>
                        </div>
                    </div>
                    <div class="fc-feature">
                        <div class="fc-icon green"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="fc-feature-name">Akses 24/7</div>
                            <div class="fc-feature-sub">(Kapan Saja)</div>
                        </div>
                    </div>
                </div>

                <div class="mini-testimonial">
                    "Sangat mudah! Surat domisili selesai dalam hitungan menit tanpa perlu antri."
                    <div class="t-source">Warga Kelurahan Talun &bull; Satisfied ★★★★★</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── SERVICES SECTION ──────────────────────────── -->
<section class="services" id="layanan">
    <div class="services-inner">
        <div class="fade-up">
            <span class="section-label">Layanan Kami</span>
            <h2 class="section-title">Semua Kebutuhan Administrasi<br>Ada di Sini</h2>
            <p class="section-subtitle">
                Nikmati kemudahan mengurus berbagai kebutuhan administrasi kelurahan tanpa perlu antri.
            </p>
        </div>

        <div class="services-grid">
            <div class="service-card fade-up delay-1">
                <div class="service-icon orange">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="service-title">Pendaftaran Penduduk</h3>
                <p class="service-desc">
                    Layanan pendaftaran lahir, pindah, dan data kependudukan online.
                </p>
            </div>

            <div class="service-card fade-up delay-2">
                <div class="service-icon green">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <h3 class="service-title">Pengajuan Surat Keterangan</h3>
                <p class="service-desc">
                    Ajukan berbagai surat keterangan resmi secara digital tanpa ribet.
                </p>
            </div>

            <div class="service-card fade-up delay-3">
                <div class="service-icon blue">
                    <i class="fas fa-list-check"></i>
                </div>
                <h3 class="service-title">Cek Status &amp; Dokumen</h3>
                <p class="service-desc">
                    Lacak status pengajuan surat Anda dan unduh dokumen yang sudah jadi.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ─── WHY CHOOSE US ─────────────────────────────── -->
<section class="why-us" id="tentang">
    <div class="why-us-inner">
        <div class="why-us-content fade-up">
            <span class="section-label">Mengapa Memilih Kami</span>
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">
                Sistem kami dirancang untuk mempermudah warga mengakses layanan kelurahan kapan saja dan di mana saja.
            </p>

            <div class="why-features">
                <div class="why-feature">
                    <div class="why-icon orange"><i class="fas fa-bolt"></i></div>
                    <div>
                        <div class="why-feature-name">Efisien <span style="color:var(--text-light); font-weight:400;">(Hemat Waktu)</span></div>
                        <div class="why-feature-sub">Proses pengajuan surat lebih cepat tanpa perlu datang ke kantor.</div>
                    </div>
                </div>

                <div class="why-feature">
                    <div class="why-icon teal"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="why-feature-name">Transparan <span style="color:var(--text-light); font-weight:400;">(Proses Terbuka)</span></div>
                        <div class="why-feature-sub">Status pengajuan dapat dipantau secara real-time kapan saja.</div>
                    </div>
                </div>

                <div class="why-feature">
                    <div class="why-icon blue"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="why-feature-name">Akses 24/7 <span style="color:var(--text-light); font-weight:400;">(Kapan Saja)</span></div>
                        <div class="why-feature-sub">Akses portal kapan pun Anda butuhkan, 24 jam sehari.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="why-us-cards fade-up delay-2">
            <div class="why-stats">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Warga Terlayani</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Jenis Layanan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Akses Portal</div>
                </div>
            </div>

            <div class="info-card highlight">
                <div class="info-title">Testimoni Warga</div>
                <div class="info-body">
                    "Sangat membantu! Proses pengajuan surat domisili jadi jauh lebih mudah dan cepat. Tidak perlu antri berjam-jam lagi."
                </div>
                <div class="info-source">Warga Kelurahan Talun &bull; Satisfied ★★★★★</div>
            </div>

            <div class="info-card">
                <div class="info-title">Keamanan Data Terjamin</div>
                <div class="info-body" style="font-style:normal; color: var(--text-mid);">
                    Data pribadi Anda dilindungi dengan sistem enkripsi terkini sesuai dengan peraturan perlindungan data yang berlaku.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── FOOTER ─────────────────────────────────────── -->
<footer id="kontak">
    <div class="footer-inner">
        <div class="footer-grid">
            <!-- Brand -->
            <div class="footer-brand">
                <div class="footer-brand-name">Kelurahan Digital</div>
                <div class="footer-brand-desc">
                    Portal administrasi digital Kelurahan Talun, Kecamatan Talun, Kabupaten Blitar. Melayani warga dengan cepat, tepat, dan transparan.
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i> Kelurahan Talun, Blitar
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i> (0342) 692 809
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-globe"></i> www.kelurahan.go.id
                </div>
            </div>

            <!-- Warga -->
            <div>
                <div class="footer-col-title">Warga</div>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Daftar Akun</a></li>
                    <li><a href="{{ route('login') }}">Masuk Portal</a></li>
                    <li><a href="#layanan">Layanan Kami</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                </ul>
            </div>

            <!-- Tautan Penting -->
            <div>
                <div class="footer-col-title">Tautan Penting</div>
                <ul class="footer-links">
                    <li><a href="/">Halaman Utama</a></li>
                    <li><a href="{{ route('login') }}">Layanan Kami</a></li>
                    <li><a href="#tentang">Panduan</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <div class="footer-col-title">Kontak</div>
                <ul class="footer-links">
                    <li><a href="#kontak">Hubungi Kami</a></li>
                    <li><a href="{{ route('login') }}">Profil</a></li>
                </ul>

                <!-- Mini building illustration -->
                <div style="margin-top: 1.5rem; opacity: .4;">
                    <svg width="90" height="70" viewBox="0 0 90 70" fill="none">
                        <rect x="15" y="30" width="60" height="38" rx="2" fill="white"/>
                        <polygon points="10,32 45,10 80,32" fill="rgba(255,255,255,.7)"/>
                        <rect x="30" y="45" width="30" height="23" rx="2" fill="rgba(26,61,43,.5)"/>
                        <rect x="20" y="36" width="12" height="10" rx="1" fill="rgba(26,61,43,.4)"/>
                        <rect x="58" y="36" width="12" height="10" rx="1" fill="rgba(26,61,43,.4)"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; {{ date('Y') }} Kelurahan Talun. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>

<script>
    // Scroll animation observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.navbar');
        if (window.scrollY > 20) {
            nav.style.background = 'rgba(26,61,43,.98)';
        } else {
            nav.style.background = 'rgba(26,61,43,.95)';
        }
    });
</script>
</body>
</html>
