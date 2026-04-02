<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="greeting-section">
                    <h1>Selamat Datang [Nama Warga]</h1>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Status Pengajuan Terakhir</p>
                            <p class="stat-status">Sedang Diproses</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon download">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Surat Terakhir Unduh</p>
                            <p class="stat-count">1</p>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11 Januari 2026</td>
                                <td>Surat Keterangan Kemiskinan</td>
                                <td><span class="status-badge status-pending">Sedang Diproses</span></td>
                            </tr>
                            <tr>
                                <td>Tambahkan data sesuai kebutuhan</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
