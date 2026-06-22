<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="greeting-section">
                    <h1>Selamat Datang {{ auth()->user()->warga?->nama_warga ?? auth()->user()->name }}</h1>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Status Pengajuan Terakhir</p>
                            <p class="stat-status">
                                @if($latestSurat)
                                    {{ ucfirst($latestSurat->status) }}
                                @else
                                    Belum ada pengajuan
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon download">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Surat Selesai / Siap Unduh</p>
                            <p class="stat-count">{{ $totalUnduh }}</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon add">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Ajukan Surat</p>
                            <p class="stat-action">Klik untuk ajukan</p>
                        </div>
                    </div>
                </div>

                <div class="activity-section">
                    <h2>Riwayat Aktivitas</h2>
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Surat</th>
                                <th>Status Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $surat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $surat->jenis_surat }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($surat->status) {
                                                'menunggu' => 'status-pending',
                                                'diproses' => 'status-pending',
                                                'selesai'  => 'status-approved',
                                                'ditolak'  => 'status-rejected',
                                                default    => 'status-pending',
                                            };
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">{{ ucfirst($surat->status) }}</span>
                                    </td>
                                    <td>
                                        @if($surat->status === 'selesai' && $surat->file_surat)
                                            <a href="{{ route('warga.surat.lihat', $surat->id) }}"
                                               target="_blank"
                                               style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; background:#f0f2f5; color:#4b5563; border: 1px solid #d1d5db; border-radius:7px; text-decoration:none; font-size:0.82rem; font-weight:700; transition:all 0.2s;"
                                               title="Lihat Surat">
                                                <i class="fas fa-file-pdf"></i> Lihat PDF
                                            </a>
                                        @else
                                            <span style="color:#bbb; font-size:0.82rem;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center;">Belum ada riwayat aktivitas</td>
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
