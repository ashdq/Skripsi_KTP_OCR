<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Petugas - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.petugas-sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="greeting-section">
                    <h1>Selamat Datang [Nama Petugas]</h1>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-file"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Total Pengajuan Bulan Ini</p>
                            <p class="stat-count">7</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon download">
                            <i class="fas fa-pen-fancy"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Menunggu Tanda Tangan</p>
                            <p class="stat-count">1</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon add">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Analisis Demografi & Sosial</p>
                            <p class="stat-action">Lihat Detail</p>
                        </div>
                    </div>
                </div>

                <div class="activity-section">
                    <h2>Pengajuan Terbaru</h2>
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>Jenis Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Dian</td>
                                <td>3312450123231</td>
                                <td>Jalan Pisang No 8</td>
                                <td>Surat Keterangan Kelahiran</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Siti Nurhaliza</td>
                                <td>3312450123232</td>
                                <td>Jalan Mangga No 5</td>
                                <td>Surat Keterangan Domisili</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>3312450123233</td>
                                <td>Jalan Jeruk No 12</td>
                                <td>Surat Keterangan Kemiskinan</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail">Lihat Detail</button>
                                        <button class="btn-action btn-verifikasi">Verifikasi</button>
                                    </div>
                                </td>
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
