<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Surat - Sistem Informasi Kelurahan</title>
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
                    <h1>Tanda Tangan Surat</h1>
                </div>

                <div class="signature-section">
                    <h2>Daftar Surat</h2>
                    <div class="signature-list">
                        <div class="signature-item">
                            <div class="item-icon">
                                <i class="fas fa-file-lines"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-name">Surat Keterangan Kelahiran</div>
                                <div class="item-applicant">Pengaju: Ahmad Dian</div>
                            </div>
                            <button class="btn-signature">Tanda Tangan</button>
                        </div>

                        <div class="signature-item">
                            <div class="item-icon">
                                <i class="fas fa-file-lines"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-name">Surat Keterangan Kematian</div>
                                <div class="item-applicant">Pengaju: Siti Nurhaliza</div>
                            </div>
                            <button class="btn-signature">Tanda Tangan</button>
                        </div>

                        <div class="signature-item">
                            <div class="item-icon">
                                <i class="fas fa-file-lines"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-name">Surat Keterangan Domisili</div>
                                <div class="item-applicant">Pengaju: Budi Santoso</div>
                            </div>
                            <button class="btn-signature">Tanda Tangan</button>
                        </div>

                        <div class="signature-item">
                            <div class="item-icon">
                                <i class="fas fa-file-lines"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-name">Surat Keterangan Kemiskinan</div>
                                <div class="item-applicant">Pengaju: Rina Wijaya</div>
                            </div>
                            <button class="btn-signature">Tanda Tangan</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
