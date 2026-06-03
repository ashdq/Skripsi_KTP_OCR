<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .filter-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: #fff;
            border: 1px solid #e0e6ed;
            border-radius: 30px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #555;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .filter-pill:hover {
            border-color: #1a472a;
            color: #1a472a;
        }

        .filter-pill.active {
            background: #1a472a;
            color: #fff;
            border-color: #1a472a;
            box-shadow: 0 4px 10px rgba(26,71,42,0.2);
        }

        .filter-badge {
            background: #f0f2f5;
            color: #555;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .filter-pill.active .filter-badge {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        /* TABLE STYLES */
        .table-container {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e0e6ed;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e0e6ed;
        }

        .data-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e0e6ed;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .pemohon-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .pemohon-nama {
            font-weight: 700;
            color: #1a2a1a;
        }

        .pemohon-nik {
            font-size: 0.75rem;
            color: #6b7280;
            font-family: monospace;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-diproses { background: #fff3cd; color: #856404; }
        .status-selesai { background: #d4edda; color: #155724; }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-ttd {
            background: linear-gradient(135deg, #1a472a, #2e7d52);
            color: #fff;
            border: none;
        }

        .btn-ttd:hover {
            background: linear-gradient(135deg, #133620, #245e3e);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(26,71,42,0.2);
            color: #fff;
        }

        .btn-lihat {
            background: #f0f2f5;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .btn-lihat:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
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
    </style>
</head>
<body class="warga-home-page">
    @include('layout.header')

    <div class="main-container">
        @include('layout.petugas-sidebar')

        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header" style="margin-bottom: 1.5rem;">
                    <h1><i class="fas fa-file-signature"></i> Tanda Tangan Surat</h1>
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

                {{-- Filters --}}
                <div class="filter-row">
                    <a href="{{ route('petugas.tanda-tangan', ['filter' => 'menunggu']) }}" class="filter-pill {{ $filter === 'menunggu' ? 'active' : '' }}">
                        <i class="fas fa-hourglass-half"></i> Menunggu TTD
                        <span class="filter-badge">{{ $countMenunggu }}</span>
                    </a>
                    <a href="{{ route('petugas.tanda-tangan', ['filter' => 'selesai']) }}" class="filter-pill {{ $filter === 'selesai' ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i> Sudah TTD
                        <span class="filter-badge">{{ $countSelesai }}</span>
                    </a>
                    <a href="{{ route('petugas.tanda-tangan', ['filter' => 'semua']) }}" class="filter-pill {{ $filter === 'semua' ? 'active' : '' }}">
                        <i class="fas fa-list"></i> Semua
                        <span class="filter-badge">{{ $countMenunggu + $countSelesai }}</span>
                    </a>
                </div>

                {{-- Table List --}}
                <div class="table-container">
                    @if($surats->count() > 0)
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Jenis Surat</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surats as $surat)
                                <tr>
                                    <td>
                                        <div class="pemohon-info">
                                            <span class="pemohon-nama">{{ $surat->ocr->nama ?? 'Tidak diketahui' }}</span>
                                            <span class="pemohon-nik">NIK: {{ $surat->ocr->nik ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td style="font-weight:600; color:#374151;">{{ $surat->jenis_surat }}</td>
                                    <td style="font-family:monospace; color:#6b7280;">
                                        {{ strtoupper(substr(str_replace(' ','',$surat->jenis_surat),0,3)) }}/{{ $surat->id }}/KEL-TLN/{{ date('Y', strtotime($surat->tanggal_pengajuan)) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if($surat->status === 'diproses')
                                            <span class="status-badge status-diproses"><i class="fas fa-clock"></i> Menunggu TTD</span>
                                        @elseif($surat->status === 'selesai')
                                            <span class="status-badge status-selesai"><i class="fas fa-check"></i> Sudah TTD</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($surat->status === 'diproses')
                                            <a href="{{ route('petugas.pengajuan.ttd.page', $surat->id) }}" class="btn-action btn-ttd">
                                                <i class="fas fa-pen-fancy"></i> Tanda Tangan
                                            </a>
                                        @elseif($surat->status === 'selesai')
                                            @if($surat->file_surat)
                                                <a href="{{ route('petugas.pengajuan.pdf', $surat->id) }}" target="_blank" class="btn-action btn-lihat">
                                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                                </a>
                                            @else
                                                <span style="font-size:0.8rem; color:#888;">File tidak ditemukan</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada surat dalam kategori ini.</p>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
