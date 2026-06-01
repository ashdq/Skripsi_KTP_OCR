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
                                Proses: {{ $proses }}
                            </span>
                            <span class="stat-badge stat-completed">
                                <i class="fas fa-check-circle"></i>
                                Selesai: {{ $selesai }}
                            </span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert-success" style="margin-bottom:1rem; padding:1rem; background-color:#d4edda; color:#155724; border-radius:4px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert-error" style="margin-bottom:1rem; padding:1rem; background-color:#f8d7da; color:#721c24; border-radius:4px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengaju</th>
                                <th>NIK</th>
                                <th>Alamat</th>
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
                                <td>{{ $surat->ocr->alamat ?? '-' }}</td>
                                <td>{{ $surat->jenis_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($surat->status) {
                                            'menunggu' => 'status-pending',
                                            'diproses' => 'status-pending',
                                            'selesai' => 'status-completed',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ ucfirst($surat->status) }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        @if($surat->status === 'menunggu')
                                        <form action="{{ route('petugas.pengajuan.proses', $surat->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action" style="background:#ffc107; color:#000;" title="Proses">
                                                <i class="fas fa-spinner"></i> Proses
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center;">Belum ada pengajuan.</td>
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
