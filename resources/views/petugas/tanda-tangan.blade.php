<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ==============================
           TANDA TANGAN PAGE STYLES
           ============================== */
        .signature-section {
            margin-top: 1rem;
        }

        .signature-section > h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a472a;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .signature-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .signature-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background: #ffffff;
            border: 1px solid #e0e6ed;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: all 0.25s ease;
        }

        .signature-item:hover {
            border-color: #1a472a;
            box-shadow: 0 4px 16px rgba(26,71,42,0.1);
            transform: translateY(-1px);
        }

        .item-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #1a472a;
            flex-shrink: 0;
        }

        .item-content {
            flex: 1;
        }

        .item-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1a2a1a;
            margin-bottom: 3px;
        }

        .item-applicant {
            font-size: 0.875rem;
            color: #555;
        }

        .item-nik {
            font-size: 0.8rem;
            color: #888;
            margin-top: 2px;
            font-family: 'Courier New', monospace;
        }

        .item-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .item-date {
            font-size: 0.78rem;
            color: #999;
        }

        .btn-generate {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.2rem;
            background: linear-gradient(135deg, #1a472a, #2e7d52);
            color: #fff;
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

        .btn-generate:hover {
            background: linear-gradient(135deg, #133620, #245e3e);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,71,42,0.3);
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state p {
            font-size: 1rem;
            font-weight: 600;
        }

        .empty-state small {
            font-size: 0.875rem;
            color: #bbb;
        }

        .stat-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .stat-pill-diproses {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body class="warga-home-page">
    @include('layout.header')

    <div class="main-container">
        @include('layout.petugas-sidebar')

        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <h1>Generate Surat</h1>
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div style="margin-bottom:1rem; padding:1rem 1.25rem; background:#d4edda; color:#155724; border-radius:8px; border:1px solid #c3e6cb; display:flex; align-items:center; gap:0.5rem; font-weight:600;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="margin-bottom:1rem; padding:1rem 1.25rem; background:#f8d7da; color:#721c24; border-radius:8px; border:1px solid #f5c6cb; display:flex; align-items:center; gap:0.5rem; font-weight:600;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="signature-section">
                    {{-- Stats --}}
                    <div class="stat-row">
                        <span class="stat-pill stat-pill-diproses">
                            <i class="fas fa-hourglass-half"></i>
                            Menunggu Generate: {{ $surats->count() }}
                        </span>
                    </div>

                    <h2>
                        <i class="fas fa-file-signature"></i>
                        Daftar Surat Yang Perlu Di-Generate
                    </h2>

                    <div class="signature-list">
                        @forelse($surats as $surat)
                        <div class="signature-item">
                            <div class="item-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-name">{{ $surat->jenis_surat }}</div>
                                <div class="item-applicant">
                                    <i class="fas fa-user" style="font-size:0.75rem; color:#1a472a;"></i>
                                    Pengaju: <strong>{{ $surat->ocr->nama ?? 'Tidak diketahui' }}</strong>
                                </div>
                                <div class="item-nik">NIK: {{ $surat->ocr->nik ?? '-' }}</div>
                            </div>
                            <div class="item-meta">
                                <span class="item-date">
                                    <i class="fas fa-calendar-alt" style="font-size:0.75rem;"></i>
                                    {{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </span>
                                <a href="{{ route('petugas.pengajuan.generate', $surat->id) }}" class="btn-generate">
                                    <i class="fas fa-eye"></i> Preview &amp; Generate
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-check-double"></i>
                            <p>Tidak ada surat yang perlu di-generate</p>
                            <small>Semua surat sudah diproses atau belum ada pengajuan yang disetujui.</small>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
