<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kelurahan Digital') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3d2b 0%, #1d4d35 55%, #164430 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background blobs */
        body::before {
            content: '';
            position: fixed;
            top: -120px; right: -120px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(76,175,120,.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Decorative dot grid */
        .bg-dots {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* ── Auth Card Wrapper ── */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
        }

        /* Back to home link */
        .auth-back {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: rgba(255,255,255,.6);
            font-size: .82rem;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 1.25rem;
            transition: color .2s;
        }

        .auth-back:hover { color: rgba(255,255,255,.9); }
        .auth-back i { font-size: .75rem; }

        /* Brand logo area */
        .auth-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2rem;
        }

        .auth-brand-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1.1rem;
            backdrop-filter: blur(8px);
        }

        .auth-brand-text {
            display: flex;
            flex-direction: column;
        }

        .auth-brand-sub {
            font-size: .65rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(255,255,255,.5);
        }

        .auth-brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }

        /* Card */
        .auth-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 32px 80px rgba(0,0,0,.3), 0 0 0 1px rgba(255,255,255,.05);
        }

        .auth-card-header {
            margin-bottom: 2rem;
        }

        .auth-card-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a3d2b;
            letter-spacing: -.03em;
            margin-bottom: .4rem;
        }

        .auth-card-subtitle {
            font-size: .875rem;
            color: #7a9a8a;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: #1c2b22;
            margin-bottom: .45rem;
        }

        .form-input {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #d4e4da;
            border-radius: 10px;
            font-size: .9rem;
            color: #1c2b22;
            font-family: 'Inter', sans-serif;
            background: #f9fbf9;
            transition: all .2s;
            outline: none;
        }

        .form-input:focus {
            border-color: #4caf78;
            background: white;
            box-shadow: 0 0 0 3px rgba(76,175,120,.12);
        }

        .form-input::placeholder { color: #aac4b4; }

        .form-input-icon-wrap {
            position: relative;
        }

        .form-input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #aac4b4;
            font-size: .9rem;
            pointer-events: none;
            transition: color .2s;
        }

        .form-input-icon-wrap .form-input {
            padding-left: 2.6rem;
        }

        .form-input-icon-wrap:focus-within .form-input-icon {
            color: #4caf78;
        }

        /* Error messages */
        .form-error {
            font-size: .78rem;
            color: #ef4444;
            margin-top: .3rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* Session status */
        .session-status {
            background: #e6f7ee;
            border: 1px solid #c3e8d4;
            color: #1d4d35;
            font-size: .85rem;
            padding: .75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
        }

        .form-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #1d4d35;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-check-label {
            font-size: .83rem;
            color: #4a6358;
        }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5ede9;
        }

        .form-divider span {
            font-size: .78rem;
            color: #aac4b4;
            font-weight: 500;
        }

        /* Primary submit button */
        .btn-auth-primary {
            width: 100%;
            padding: .875rem;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #1d4d35, #2e6b4a);
            color: white;
            font-size: .95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 16px rgba(29,77,53,.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            margin-top: 1.75rem;
        }

        .btn-auth-primary:hover {
            background: linear-gradient(135deg, #164430, #1d4d35);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(29,77,53,.4);
        }

        .btn-auth-primary:active { transform: translateY(0); }

        /* Footer links */
        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: .85rem;
            color: #7a9a8a;
        }

        .auth-footer a {
            color: #1d4d35;
            font-weight: 700;
            text-decoration: none;
            transition: color .2s;
        }

        .auth-footer a:hover { color: #4caf78; }

        .auth-link {
            font-size: .82rem;
            color: #4caf78;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
        }

        .auth-link:hover { color: #2e6b4a; }

        /* Security badge */
        .auth-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            margin-top: 1.5rem;
            font-size: .75rem;
            color: rgba(255,255,255,.4);
        }

        .auth-security i { font-size: .7rem; color: #4caf78; }
    </style>
</head>
<body>
    <div class="bg-dots"></div>

    <div class="auth-wrapper">
        <a href="/" class="auth-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="auth-brand">
            <div class="auth-brand-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="auth-brand-text">
                <span class="auth-brand-sub">Sistem Informasi Kelurahan</span>
                <span class="auth-brand-name">Kelurahan Digital</span>
            </div>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>

        <div class="auth-security">
            <i class="fas fa-lock"></i>
            Koneksi aman & data terenkripsi
        </div>
    </div>
</body>
</html>
