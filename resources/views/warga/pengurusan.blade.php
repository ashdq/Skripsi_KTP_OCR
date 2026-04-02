<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Surat - Sistem Informasi Kelurahan</title>
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
                    <h1>Formulir Pengajuan Surat</h1>
                </div>

                <div class="form-section">
                    <form method="POST" action="{{ url('/warga/pengurusan/submit') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih Layanan Surat -->
                        <div class="form-group-section">
                            <label class="section-label">Pilih Layanan Surat</label>
                            <div class="form-group">
                                <select name="jenis_surat" class="form-select" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    <optgroup label="Administrasi Kependudukan">
                                        <option value="kelahiran">Surat Keterangan Kelahiran</option>
                                        <option value="kematian">Surat Keterangan Kematian</option>
                                        <option value="domisili">Surat Keterangan Domisili</option>
                                        <option value="nikah">Surat Keterangan Nikah</option>
                                    </optgroup>
                                    <optgroup label="Umum">
                                        <option value="kemiskinan">Surat Keterangan Kemiskinan</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- Unggah Dokumen Pendukung -->
                        <div class="form-group-section">
                            <label class="section-label">Unggah Dokumen Pendukung</label>
                            
                            <div class="upload-container">
                                <div class="upload-grid">
                                    <!-- Upload KTP -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h3>KTP</h3>
                                        </div>
                                        <div class="upload-box">
                                            <input type="file" id="ktp" name="ktp" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <label for="ktp" class="file-label">
                                                <i class="fas fa-cloud-arrow-up"></i>
                                                <span>Browse</span>
                                            </label>
                                            <p class="upload-filename" id="ktp-filename">Tidak ada file dipilih</p>
                                        </div>
                                    </div>

                                    <!-- Upload KK -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h3>KK</h3>
                                        </div>
                                        <div class="upload-box">
                                            <input type="file" id="kk" name="kk" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <label for="kk" class="file-label">
                                                <i class="fas fa-cloud-arrow-up"></i>
                                                <span>Browse</span>
                                            </label>
                                            <p class="upload-filename" id="kk-filename">Tidak ada file dipilih</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Kirim -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')

    <script>
        // Update filename when file is selected
        document.getElementById('ktp').addEventListener('change', function(e) {
            const filename = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('ktp-filename').textContent = filename;
        });

        document.getElementById('kk').addEventListener('change', function(e) {
            const filename = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('kk-filename').textContent = filename;
        });
    </script>
</body>
</html>
