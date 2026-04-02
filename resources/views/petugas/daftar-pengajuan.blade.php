<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
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
                                Total: 7
                            </span>
                            <span class="stat-badge stat-pending">
                                <i class="fas fa-hourglass-half"></i>
                                Proses: 3
                            </span>
                            <span class="stat-badge stat-completed">
                                <i class="fas fa-check-circle"></i>
                                Selesai: 4
                            </span>
                        </div>
                    </div>

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
                            <tr>
                                <td>1</td>
                                <td>Ahmad Dian</td>
                                <td>3312450123231</td>
                                <td>Jalan Pisang No 8</td>
                                <td>Surat Keterangan Kelahiran</td>
                                <td>10 Januari 2026</td>
                                <td><span class="status-badge status-pending">Diproses</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Siti Nurhaliza</td>
                                <td>3312450123232</td>
                                <td>Jalan Mangga No 5</td>
                                <td>Surat Keterangan Domisili</td>
                                <td>11 Januari 2026</td>
                                <td><span class="status-badge status-pending">Diproses</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Budi Santoso</td>
                                <td>3312450123233</td>
                                <td>Jalan Jeruk No 12</td>
                                <td>Surat Keterangan Kemiskinan</td>
                                <td>12 Januari 2026</td>
                                <td><span class="status-badge status-completed">Selesai</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Rina Wijaya</td>
                                <td>3312450123234</td>
                                <td>Jalan Apel No 3</td>
                                <td>Surat Keterangan Kematian</td>
                                <td>08 Januari 2026</td>
                                <td><span class="status-badge status-completed">Selesai</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Dina Kusmawati</td>
                                <td>3312450123235</td>
                                <td>Jalan Nanas No 7</td>
                                <td>Surat Keterangan Kelahiran</td>
                                <td>09 Januari 2026</td>
                                <td><span class="status-badge status-pending">Diproses</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </button>
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
