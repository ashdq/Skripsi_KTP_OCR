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

                        <!-- Tombol Lanjut -->
                        <div class="form-actions">
                            <button type="button" class="btn-submit" id="btn-lanjut">Lanjutkan</button>
                        </div>
                    </form>

                    <!-- Form Data Identitas (Tersembunyi) -->
                    <div id="identitas-section" class="identitas-section hidden">
                        <div class="identitas-header">
                            <h2>Data Identitas Diri</h2>
                            <p>Lengkapi data identitas Anda untuk pengajuan surat</p>
                        </div>

                        <form method="POST" action="{{ url('/warga/pengurusan/submit') }}" enctype="multipart/form-data" id="form-pengurusan-lengkap">
                            @csrf

                            <!-- Grid Data Identitas -->
                            <div class="identitas-grid">
                                <!-- Nama Lengkap -->
                                <div class="form-group">
                                    <label for="nama" class="form-label">
                                        <i class="fas fa-user"></i> Nama Lengkap
                                    </label>
                                    <input type="text" id="nama" name="nama" class="form-input" placeholder="Masukkan nama lengkap" required>
                                </div>

                                <!-- NIK -->
                                <div class="form-group">
                                    <label for="nik" class="form-label">
                                        <i class="fas fa-id-card"></i> Nomor NIK
                                    </label>
                                    <input type="text" id="nik" name="nik" class="form-input" placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                                </div>

                                <!-- Nomor KK -->
                                <div class="form-group">
                                    <label for="nomor_kk" class="form-label">
                                        <i class="fas fa-users"></i> Nomor Kartu Keluarga
                                    </label>
                                    <input type="text" id="nomor_kk" name="nomor_kk" class="form-input" placeholder="Masukkan 16 digit Nomor KK" maxlength="16" required>
                                </div>

                                <!-- Tempat Lahir -->
                                <div class="form-group">
                                    <label for="tempat_lahir" class="form-label">
                                        <i class="fas fa-map-marker"></i> Tempat Lahir
                                    </label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-input" placeholder="Masukkan tempat lahir" required>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="form-group">
                                    <label for="tanggal_lahir" class="form-label">
                                        <i class="fas fa-calendar"></i> Tanggal Lahir
                                    </label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-input" required>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">
                                        <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                    </label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-input" required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="laki-laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <!-- Agama -->
                                <div class="form-group">
                                    <label for="agama" class="form-label">
                                        <i class="fas fa-heart"></i> Agama
                                    </label>
                                    <select id="agama" name="agama" class="form-input" required>
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="islam">Islam</option>
                                        <option value="kristen">Kristen</option>
                                        <option value="katolik">Katolik</option>
                                        <option value="hindu">Hindu</option>
                                        <option value="budha">Budha</option>
                                        <option value="konhucu">Kong Hu Cu</option>
                                    </select>
                                </div>

                                <!-- Status Perkawinan -->
                                <div class="form-group">
                                    <label for="status_perkawinan" class="form-label">
                                        <i class="fas fa-ring"></i> Status Perkawinan
                                    </label>
                                    <select id="status_perkawinan" name="status_perkawinan" class="form-input" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="belum_kawin">Belum Kawin</option>
                                        <option value="kawin">Kawin</option>
                                        <option value="cerai_hidup">Cerai Hidup</option>
                                        <option value="cerai_mati">Cerai Mati</option>
                                    </select>
                                </div>

                                <!-- Pekerjaan -->
                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">
                                        <i class="fas fa-briefcase"></i> Pekerjaan
                                    </label>
                                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-input" placeholder="Masukkan pekerjaan" required>
                                </div>

                                <!-- Alamat -->
                                <div class="form-group form-group-full">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-address-card"></i> Alamat Lengkap
                                    </label>
                                    <textarea id="alamat" name="alamat" class="form-input" placeholder="Masukkan alamat lengkap" rows="3" required></textarea>
                                </div>

                                <!-- RT/RW -->
                                <div class="form-group">
                                    <label for="rt" class="form-label">
                                        <i class="fas fa-home"></i> RT
                                    </label>
                                    <input type="text" id="rt" name="rt" class="form-input" placeholder="RT" maxlength="3" required>
                                </div>

                                <div class="form-group">
                                    <label for="rw" class="form-label">
                                        <i class="fas fa-home"></i> RW
                                    </label>
                                    <input type="text" id="rw" name="rw" class="form-input" placeholder="RW" maxlength="3" required>
                                </div>

                                <!-- Kelurahan/Desa -->
                                <div class="form-group">
                                    <label for="kelurahan" class="form-label">
                                        <i class="fas fa-map"></i> Kelurahan/Desa
                                    </label>
                                    <input type="text" id="kelurahan" name="kelurahan" class="form-input" placeholder="Kelurahan/Desa" required>
                                </div>

                                <!-- Kecamatan -->
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">
                                        <i class="fas fa-map"></i> Kecamatan
                                    </label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-input" placeholder="Kecamatan" required>
                                </div>

                                <!-- Kota -->
                                <div class="form-group">
                                    <label for="kota" class="form-label">
                                        <i class="fas fa-city"></i> Kota/Kabupaten
                                    </label>
                                    <input type="text" id="kota" name="kota" class="form-input" placeholder="Kota/Kabupaten" required>
                                </div>

                                <!-- Provinsi -->
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">
                                        <i class="fas fa-map-location-dot"></i> Provinsi
                                    </label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-input" placeholder="Provinsi" required>
                                </div>

                                <!-- Kode Pos -->
                                <div class="form-group">
                                    <label for="kode_pos" class="form-label">
                                        <i class="fas fa-envelope"></i> Kode Pos
                                    </label>
                                    <input type="text" id="kode_pos" name="kode_pos" class="form-input" placeholder="Kode Pos" maxlength="5" required>
                                </div>

                                <!-- No Telepon -->
                                <div class="form-group">
                                    <label for="no_telepon" class="form-label">
                                        <i class="fas fa-phone"></i> No Telepon
                                    </label>
                                    <input type="tel" id="no_telepon" name="no_telepon" class="form-input" placeholder="08xx-xxxx-xxxx" required>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions-identitas">
                                <button type="button" class="btn-batal" id="btn-batal">Batal</button>
                                <button type="submit" class="btn-submit">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')

    <script>
        const ktpInput = document.getElementById('ktp');
        const kkInput = document.getElementById('kk');
        const btnLanjut = document.getElementById('btn-lanjut');
        const btnBatal = document.getElementById('btn-batal');
        const uploadForm = document.querySelector('.form-section form');
        const identitasSection = document.getElementById('identitas-section');

        // Update filename when file is selected
        ktpInput.addEventListener('change', function(e) {
            const filename = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('ktp-filename').textContent = filename;
        });

        kkInput.addEventListener('change', function(e) {
            const filename = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('kk-filename').textContent = filename;
        });

        // Handle Lanjut Button
        btnLanjut.addEventListener('click', function(e) {
            e.preventDefault();

            // Validasi file upload
            if (!ktpInput.files.length) {
                alert('⚠️ Silakan upload file KTP terlebih dahulu');
                ktpInput.focus();
                return;
            }

            if (!kkInput.files.length) {
                alert('⚠️ Silakan upload file KK terlebih dahulu');
                kkInput.focus();
                return;
            }

            // Update hidden inputs dengan jenis surat
            const jenisSurat = document.querySelector('select[name="jenis_surat"]');
            const jenisSuratInput = document.createElement('input');
            jenisSuratInput.type = 'hidden';
            jenisSuratInput.name = 'jenis_surat';
            jenisSuratInput.value = jenisSurat.value;
            document.getElementById('form-pengurusan-lengkap').appendChild(jenisSuratInput);

            // Copy file inputs ke form identitas
            const formData = new FormData();
            formData.append('ktp', ktpInput.files[0]);
            formData.append('kk', kkInput.files[0]);

            // Store di hidden inputs
            const ktpHidden = document.createElement('input');
            ktpHidden.type = 'hidden';
            ktpHidden.name = 'ktp';
            ktpHidden.value = ktpInput.value;

            const kkHidden = document.createElement('input');
            kkHidden.type = 'hidden';
            kkHidden.name = 'kk';
            kkHidden.value = kkInput.value;

            // Hide upload form, show identitas form
            uploadForm.style.display = 'none';
            identitasSection.classList.remove('hidden');

            // Smooth scroll to identitas section
            setTimeout(() => {
                identitasSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        });

        // Handle Batal Button
        btnBatal.addEventListener('click', function(e) {
            e.preventDefault();

            // Show upload form, hide identitas form
            uploadForm.style.display = 'block';
            identitasSection.classList.add('hidden');

            // Smooth scroll to top
            setTimeout(() => {
                document.querySelector('.page-header').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        });
    </script>
</body>
</html>
