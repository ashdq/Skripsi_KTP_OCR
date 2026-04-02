<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduh Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
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

                <div class="unduh-section">
                    <h2>Daftar Surat</h2>
                    <div class="letter-list">
                        <div class="letter-item">
                            <div class="letter-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="letter-info">
                                <p class="letter-name">Surat Keterangan Kelahiran</p>
                            </div>
                            <div class="letter-action">
                                <button class="btn-unduh">Unduh Surat</button>
                            </div>
                        </div>

                        <div class="letter-item">
                            <div class="letter-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="letter-info">
                                <p class="letter-name">Surat Keterangan Kematian</p>
                            </div>
                            <div class="letter-action">
                                <button class="btn-unduh">Unduh Surat</button>
                            </div>
                        </div>

                        <div class="letter-item">
                            <div class="letter-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="letter-info">
                                <p class="letter-name">Surat Keterangan Domisili</p>
                            </div>
                            <div class="letter-action">
                                <button class="btn-unduh">Unduh Surat</button>
                            </div>
                        </div>

                        <div class="letter-item">
                            <div class="letter-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="letter-info">
                                <p class="letter-name">Surat Keterangan Kemiskinan</p>
                            </div>
                            <div class="letter-action">
                                <button class="btn-unduh">Unduh Surat</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')
</body>
</html>
