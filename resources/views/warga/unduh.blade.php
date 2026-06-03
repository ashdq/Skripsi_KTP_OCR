<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduh Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ============================
           UNDUH SURAT PAGE STYLES
           ============================ */
        .unduh-section h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a472a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .letter-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }

        .letter-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            background: white;
            border: 1px solid #e0e6ed;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.25s ease;
        }

        .letter-item:hover {
            box-shadow: 0 6px 18px rgba(26,71,42,0.1);
            border-color: #1a472a;
            transform: translateY(-2px);
        }

        .letter-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: 10px;
            font-size: 1.4rem;
            color: #1a472a;
            flex-shrink: 0;
        }

        .letter-info {
            flex: 1;
        }

        .letter-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a2a1a;
            margin: 0 0 4px 0;
            line-height: 1.4;
        }

        .letter-date {
            font-size: 0.78rem;
            color: #888;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .letter-action {
            flex-shrink: 0;
        }

        .btn-unduh {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.1rem;
            background: linear-gradient(135deg, #1a472a, #2e7d52);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-unduh:hover {
            background: linear-gradient(135deg, #133620, #245e3e);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,71,42,0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: #aaa;
        }

        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.25rem;
            padding: 0.65rem 1.25rem;
            background: #1a472a;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .empty-state a:hover {
            background: #133620;
        }

        /* Stat summary */
        .stat-summary {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-pill-success {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            background: #d4edda;
            color: #155724;
        }

        /* Flash messages */
        .flash-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.25rem;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="warga-home-page">
    @include('layout.header')

    <div class="main-container">
        @include('layout.sidebar')

        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <h1>Unduh Surat</h1>
                </div>

                {{-- Flash error --}}
                @if(session('error'))
                    <div class="flash-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="unduh-section">
                    {{-- Jumlah surat tersedia --}}
                    @if($surats->count() > 0)
                        <div class="stat-summary">
                            <span class="stat-pill-success">
                                <i class="fas fa-check-circle"></i>
                                {{ $surats->count() }} surat siap diunduh
                            </span>
                        </div>
                    @endif

                    <h2>
                        <i class="fas fa-folder-open"></i>
                        Daftar Surat
                    </h2>

                    @if($surats->count() > 0)
                        <div class="letter-list">
                            @foreach($surats as $surat)
                            <div class="letter-item">
                                <div class="letter-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="letter-info">
                                    <p class="letter-name">{{ $surat->jenis_surat }}</p>
                                    <span class="letter-date">
                                        <i class="fas fa-calendar-check"></i>
                                        Selesai: {{ \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <div class="letter-action">
                                    <a href="{{ route('warga.surat.unduh', $surat->id) }}"
                                       class="btn-unduh"
                                       title="Unduh {{ $surat->jenis_surat }}">
                                        <i class="fas fa-download"></i> Unduh Surat
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h3>Belum Ada Surat Tersedia</h3>
                            <p>Surat Anda akan muncul di sini setelah pengajuan diproses dan selesai oleh petugas.</p>
                            <a href="{{ route('warga.pengurusan') }}">
                                <i class="fas fa-plus"></i> Ajukan Surat Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
