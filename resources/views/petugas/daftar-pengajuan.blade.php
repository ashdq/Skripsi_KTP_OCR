<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.petugas-sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <h1>Daftar Pengajuan</h1>
                </div>

                <div class="table-section">
                    <div class="table-header">
                        <h2>Data Pengajuan Surat</h2>
                        <div class="table-stats">
                            <span class="stat-badge stat-total">
                                <i class="fas fa-file-circle-check"></i>
                                Total: {{ $total }}
                            </span>
                            <span class="stat-badge stat-pending">
                                <i class="fas fa-hourglass-half"></i>
                                Diproses: {{ $proses }}
                            </span>
                            <span class="stat-badge stat-completed">
                                <i class="fas fa-check-circle"></i>
                                Selesai: {{ $selesai }}
                            </span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert-success" style="margin-bottom:1rem; padding:1rem; background-color:#d4edda; color:#155724; border-radius:8px; display:flex; align-items:center; gap:0.5rem;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert-error" style="margin-bottom:1rem; padding:1rem; background-color:#f8d7da; color:#721c24; border-radius:8px; display:flex; align-items:center; gap:0.5rem;">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengaju</th>
                                <th>NIK</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $index => $surat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $surat->ocr->nama ?? '-' }}</td>
                                <td>{{ $surat->ocr->nik ?? '-' }}</td>
                                <td>{{ $surat->jenis_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($surat->status) {
                                            'menunggu' => 'status-pending',
                                            'diproses' => 'status-process',
                                            'selesai'  => 'status-completed',
                                            default    => 'status-pending',
                                        };
                                        $badgeLabel = match($surat->status) {
                                            'menunggu' => 'Menunggu',
                                            'diproses' => 'Diproses',
                                            'selesai'  => 'Selesai',
                                            default    => ucfirst($surat->status),
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Lihat Detail --}}
                                        <a href="{{ route('petugas.pengajuan.detail', $surat->id) }}"
                                           class="btn-action btn-detail"
                                           title="Lihat Detail"
                                           style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Generate Surat: muncul saat status menunggu atau diproses --}}
                                        @if(in_array($surat->status, ['menunggu', 'diproses']))
                                        <a href="{{ route('petugas.pengajuan.generate', $surat->id) }}"
                                           class="btn-action"
                                           title="Generate Surat"
                                           style="background:linear-gradient(135deg,#1a472a,#2e7d52); color:#fff; display:inline-flex; align-items:center; gap:0.3rem; text-decoration:none; padding:0.45rem 0.85rem; border-radius:7px; font-weight:700; font-size:0.82rem;">
                                            <i class="fas fa-file-medical"></i> Generate
                                        </a>
                                        @endif

                                        {{-- Surat selesai --}}
                                        @if($surat->status === 'selesai')
                                        <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.45rem 0.85rem; background:#d4edda; color:#155724; border-radius:7px; font-size:0.82rem; font-weight:700;">
                                            <i class="fas fa-check-double"></i> Selesai
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding:2rem; color:#aaa;">
                                    <i class="fas fa-inbox" style="font-size:2rem; margin-bottom:0.5rem; display:block;"></i>
                                    Belum ada pengajuan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
