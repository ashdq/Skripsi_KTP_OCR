<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @php
                    $hasExistingKtp = !empty($existingKtp);
                    $hasExistingKk = !empty($existingKk);
                @endphp

                <div class="document-status-grid">
                    <div class="document-status-card {{ $hasExistingKtp ? 'is-ready' : 'is-empty' }}">
                        <div class="document-status-title">KTP</div>
                        <div class="document-status-body">
                            @if ($hasExistingKtp)
                                <i class="fas fa-file-circle-check"></i>
                                <span>{{ $existingKtp['name'] }}</span>
                                <div class="document-status-actions">
                                    <button type="button" class="btn-preview-document" data-preview-url="{{ $existingKtp['preview_url'] }}" data-preview-name="KTP - {{ $existingKtp['name'] }}" data-preview-type="{{ $existingKtp['type'] }}">Lihat</button>
                                    <a class="btn-download-document" href="{{ $existingKtp['download_url'] }}">Unduh</a>
                                </div>
                            @else
                                <i class="fas fa-file-circle-xmark"></i>
                                <span>Belum ada dokumen tersimpan</span>
                            @endif
                        </div>
                    </div>

                    <div class="document-status-card {{ $hasExistingKk ? 'is-ready' : 'is-empty' }}">
                        <div class="document-status-title">KK</div>
                        <div class="document-status-body">
                            @if ($hasExistingKk)
                                <i class="fas fa-file-circle-check"></i>
                                <span>{{ $existingKk['name'] }}</span>
                                <div class="document-status-actions">
                                    <button type="button" class="btn-preview-document" data-preview-url="{{ $existingKk['preview_url'] }}" data-preview-name="KK - {{ $existingKk['name'] }}" data-preview-type="{{ $existingKk['type'] }}">Lihat</button>
                                    <a class="btn-download-document" href="{{ $existingKk['download_url'] }}">Unduh</a>
                                </div>
                            @else
                                <i class="fas fa-file-circle-xmark"></i>
                                <span>Belum ada dokumen tersimpan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <form method="POST" action="{{ route('warga.pengurusan.submit') }}" enctype="multipart/form-data" id="dokumen-form">
                        @csrf

                        <div id="upload-step">
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
                                            <input type="file" id="ktp" name="ktp" class="file-input" accept=".pdf,.jpg,.jpeg,.png">
                                            <label for="ktp" class="file-label">
                                                <i class="fas fa-cloud-arrow-up"></i>
                                                <span>{{ $hasExistingKtp ? 'Ganti KTP' : 'Browse' }}</span>
                                            </label>
                                            <p class="upload-filename" id="ktp-filename">{{ $hasExistingKtp ? $existingKtp['name'] : 'Tidak ada file dipilih' }}</p>
                                        </div>
                                    </div>

                                    <!-- Upload KK -->
                                    <div class="upload-item">
                                        <div class="upload-header">
                                            <h3>KK</h3>
                                        </div>
                                        <div class="upload-box">
                                            <input type="file" id="kk" name="kk" class="file-input" accept=".pdf,.jpg,.jpeg,.png">
                                            <label for="kk" class="file-label">
                                                <i class="fas fa-cloud-arrow-up"></i>
                                                <span>{{ $hasExistingKk ? 'Ganti KK' : 'Browse' }}</span>
                                            </label>
                                            <p class="upload-filename" id="kk-filename">{{ $hasExistingKk ? $existingKk['name'] : 'Tidak ada file dipilih' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Lanjut -->
                        <div class="form-actions">
                            <button type="button" class="btn-submit" id="btn-lanjut">Lanjutkan</button>
                        </div>
                        </div>

                    <!-- Form Data Identitas (Tersembunyi) -->
                    <div id="identitas-section" class="identitas-section hidden">
                        <div class="identitas-header">
                            <h2>Data Identitas Diri</h2>
                            <p>Lengkapi data identitas Anda untuk pengajuan surat</p>
                        </div>

                            <!-- Grid Data Identitas -->
                            <div class="identitas-grid">
                                <!-- Nama Lengkap -->
                                <div class="form-group">
                                    <label for="nama" class="form-label">
                                        <i class="fas fa-user"></i> Nama Lengkap
                                    </label>
                                    <input type="text" id="nama" name="nama" class="form-input" value="{{ old('nama', optional($ocrData ?? null)->nama) }}" placeholder="Masukkan nama lengkap" disabled required>
                                </div>

                                <!-- NIK -->
                                <div class="form-group">
                                    <label for="nik" class="form-label">
                                        <i class="fas fa-id-card"></i> Nomor NIK
                                    </label>
                                    <input type="text" id="nik" name="nik" class="form-input" value="{{ old('nik', optional($ocrData ?? null)->nik) }}" placeholder="Masukkan 16 digit NIK" maxlength="16" disabled required>
                                </div>

                                <!-- Nomor KK -->
                                <div class="form-group">
                                    <label for="nomor_kk" class="form-label">
                                        <i class="fas fa-users"></i> Nomor Kartu Keluarga
                                    </label>
                                    <input type="text" id="nomor_kk" name="nomor_kk" class="form-input" value="{{ old('nomor_kk', optional($ocrData ?? null)->nomor_kk) }}" placeholder="Masukkan 16 digit Nomor KK" maxlength="16" disabled required>
                                </div>

                                <!-- Tempat Lahir -->
                                <div class="form-group">
                                    <label for="tempat_lahir" class="form-label">
                                        <i class="fas fa-map-marker"></i> Tempat Lahir
                                    </label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-input" value="{{ old('tempat_lahir', optional($ocrData ?? null)->tempat_lahir) }}" placeholder="Masukkan tempat lahir" disabled required>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="form-group">
                                    <label for="tanggal_lahir" class="form-label">
                                        <i class="fas fa-calendar"></i> Tanggal Lahir
                                    </label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir', optional($ocrData ?? null)->tanggal_lahir) }}" disabled required>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">
                                        <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                    </label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-input" disabled required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="laki-laki" {{ old('jenis_kelamin', optional($ocrData ?? null)->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jenis_kelamin', optional($ocrData ?? null)->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <!-- Agama -->
                                <div class="form-group">
                                    <label for="agama" class="form-label">
                                        <i class="fas fa-heart"></i> Agama
                                    </label>
                                    <select id="agama" name="agama" class="form-input" disabled required>
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="islam" {{ old('agama', optional($ocrData ?? null)->agama) == 'islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="kristen" {{ old('agama', optional($ocrData ?? null)->agama) == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="katolik" {{ old('agama', optional($ocrData ?? null)->agama) == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="hindu" {{ old('agama', optional($ocrData ?? null)->agama) == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="budha" {{ old('agama', optional($ocrData ?? null)->agama) == 'budha' ? 'selected' : '' }}>Budha</option>
                                        <option value="konhucu" {{ old('agama', optional($ocrData ?? null)->agama) == 'konhucu' ? 'selected' : '' }}>Kong Hu Cu</option>
                                    </select>
                                </div>

                                <!-- Status Perkawinan -->
                                <div class="form-group">
                                    <label for="status_perkawinan" class="form-label">
                                        <i class="fas fa-ring"></i> Status Perkawinan
                                    </label>
                                    <select id="status_perkawinan" name="status_perkawinan" class="form-input" disabled required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="belum_kawin" {{ old('status_perkawinan', optional($ocrData ?? null)->status_perkawinan) == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                        <option value="kawin" {{ old('status_perkawinan', optional($ocrData ?? null)->status_perkawinan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                                        <option value="cerai_hidup" {{ old('status_perkawinan', optional($ocrData ?? null)->status_perkawinan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                        <option value="cerai_mati" {{ old('status_perkawinan', optional($ocrData ?? null)->status_perkawinan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                                    </select>
                                </div>

                                <!-- Pekerjaan -->
                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">
                                        <i class="fas fa-briefcase"></i> Pekerjaan
                                    </label>
                                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-input" value="{{ old('pekerjaan', optional($ocrData ?? null)->pekerjaan) }}" placeholder="Masukkan pekerjaan" disabled required>
                                </div>

                                <!-- Alamat -->
                                <div class="form-group form-group-full">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-address-card"></i> Alamat Lengkap
                                    </label>
                                    <textarea id="alamat" name="alamat" class="form-input" placeholder="Masukkan alamat lengkap" rows="3" disabled required>{{ old('alamat', optional($ocrData ?? null)->alamat) }}</textarea>
                                </div>

                                <!-- RT/RW -->
                                <div class="form-group">
                                    <label for="rt" class="form-label">
                                        <i class="fas fa-home"></i> RT/RW
                                    </label>
                                    <input type="text" id="rt" name="rt" class="form-input" value="{{ old('rt', optional($ocrData ?? null)->rt_rw) }}" placeholder="RT/RW" maxlength="3" disabled required>
                                </div>

                                <!-- Kelurahan/Desa -->
                                <div class="form-group">
                                    <label for="kelurahan" class="form-label">
                                        <i class="fas fa-map"></i> Kelurahan/Desa
                                    </label>
                                    <input type="text" id="kelurahan" name="kelurahan" class="form-input" value="{{ old('kelurahan', optional($ocrData ?? null)->kelurahan) }}" placeholder="Kelurahan/Desa" disabled required>
                                </div>

                                <!-- Kecamatan -->
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">
                                        <i class="fas fa-map"></i> Kecamatan
                                    </label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-input" value="{{ old('kecamatan', optional($ocrData ?? null)->kecamatan) }}" placeholder="Kecamatan" disabled required>
                                </div>

                                <!-- Kota -->
                                <div class="form-group">
                                    <label for="kota" class="form-label">
                                        <i class="fas fa-city"></i> Kota/Kabupaten
                                    </label>
                                    <input type="text" id="kota" name="kota" class="form-input" value="{{ old('kota', optional($ocrData ?? null)->kota_kabupaten) }}" placeholder="Kota/Kabupaten" disabled required>
                                </div>

                                <!-- Provinsi -->
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">
                                        <i class="fas fa-map-location-dot"></i> Provinsi
                                    </label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-input" value="{{ old('provinsi', optional($ocrData ?? null)->provinsi) }}" placeholder="Provinsi" disabled required>
                                </div>

                                <!-- No Telepon -->
                                <div class="form-group">
                                    <label for="no_telepon" class="form-label">
                                        <i class="fas fa-phone"></i> No Telepon
                                    </label>
                                    <input type="tel" id="no_telepon" name="no_telepon" class="form-input" value="{{ old('no_telepon', optional(auth()->user()->warga)->nomor_hp) }}" placeholder="08xx-xxxx-xxxx" disabled required>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions-identitas">
                                <button type="button" class="btn-batal" id="btn-batal">Batal</button>
                                <button type="button" class="btn-submit" id="btn-simpan-identitas" data-state="simpan">Simpan Data</button>
                            </div>
                    </div>
                    </form>
                    </div>
                </div>
            </div>

            <div id="preview-modal" class="preview-modal hidden">
                <div class="preview-modal-overlay" id="preview-modal-overlay"></div>
                <div class="preview-modal-content">
                    <div class="preview-modal-header">
                        <h2 id="preview-modal-title">Preview Dokumen</h2>
                        <button type="button" class="preview-modal-close" id="preview-modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="preview-modal-body">
                        <img id="preview-modal-image" class="preview-media hidden" alt="Preview dokumen">
                        <iframe id="preview-modal-pdf" class="preview-media hidden" title="Preview dokumen"></iframe>
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
        const uploadStep = document.getElementById('upload-step');
        const identitasSection = document.getElementById('identitas-section');
        const dokumenForm = document.getElementById('dokumen-form');
        const identitasInputs = identitasSection.querySelectorAll('input, select, textarea');
        const btnSimpanIdentitas = document.getElementById('btn-simpan-identitas');
        const hasExistingKtp = @json($hasExistingKtp ?? false);
        const hasExistingKk = @json($hasExistingKk ?? false);
        const previewModal = document.getElementById('preview-modal');
        const previewModalOverlay = document.getElementById('preview-modal-overlay');
        const previewModalClose = document.getElementById('preview-modal-close');
        const previewModalTitle = document.getElementById('preview-modal-title');
        const previewModalImage = document.getElementById('preview-modal-image');
        const previewModalPdf = document.getElementById('preview-modal-pdf');
        const previewButtons = document.querySelectorAll('.btn-preview-document');

        function setIdentitasDisabled(state) {
            identitasInputs.forEach((element) => {
                element.disabled = state;
            });
        }

        setIdentitasDisabled(true);

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

            const jenisSurat = document.querySelector('select[name="jenis_surat"]');
            const csrfToken = dokumenForm.querySelector('input[name="_token"]').value;
            const ktpSelected = ktpInput.files.length > 0;
            const kkSelected = kkInput.files.length > 0;

            // Validasi file upload jika belum pernah ada dokumen
            if (!ktpSelected && !hasExistingKtp) {
                alert('⚠️ Silakan upload file KTP terlebih dahulu');
                ktpInput.focus();
                return;
            }

            if (!kkSelected && !hasExistingKk) {
                alert('⚠️ Silakan upload file KK terlebih dahulu');
                kkInput.focus();
                return;
            }

            if (!jenisSurat.value) {
                alert('⚠️ Silakan pilih jenis surat terlebih dahulu');
                jenisSurat.focus();
                return;
            }

            if (!ktpSelected && !kkSelected && (hasExistingKtp || hasExistingKk)) {
                uploadStep.classList.add('hidden');
                identitasSection.classList.remove('hidden');
                setIdentitasDisabled(false);

                setTimeout(() => {
                    identitasSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);

                return;
            }

            const payload = new FormData();
            payload.append('_token', csrfToken);
            payload.append('jenis_surat', jenisSurat.value);

            if (ktpSelected) {
                payload.append('ktp', ktpInput.files[0]);
            }

            if (kkSelected) {
                payload.append('kk', kkInput.files[0]);
            }

            btnLanjut.disabled = true;
            btnLanjut.textContent = 'Mengunggah...';

            fetch(dokumenForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: payload,
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal mengunggah dokumen.');
                    }

                    return data;
                })
                .then((data) => {
                    if (ktpSelected) {
                        document.getElementById('ktp-filename').textContent = data.ktp ? data.ktp.split('/').pop() : ktpInput.files[0].name;
                    }

                    if (kkSelected) {
                        document.getElementById('kk-filename').textContent = data.kk ? data.kk.split('/').pop() : kkInput.files[0].name;
                    }

                    uploadStep.classList.add('hidden');
                    identitasSection.classList.remove('hidden');
                    setIdentitasDisabled(false);

                    // --- Auto-fill form dari hasil OCR ---
                    if (data.ocr_fields) {
                        const f = data.ocr_fields;

                        // Text inputs: mapping field OCR -> ID input form
                        const textMap = {
                            'nama': 'nama',
                            'nik': 'nik',
                            'nomor_kk': 'nomor_kk',
                            'tempat_lahir': 'tempat_lahir',
                            'pekerjaan': 'pekerjaan',
                            'alamat': 'alamat',
                            'rt_rw': 'rt',
                            'kelurahan': 'kelurahan',
                            'kecamatan': 'kecamatan',
                            'kota_kabupaten': 'kota',
                            'provinsi': 'provinsi',
                        };

                        for (const [ocrKey, inputId] of Object.entries(textMap)) {
                            if (f[ocrKey]) {
                                const el = document.getElementById(inputId);
                                if (el) el.value = f[ocrKey];
                            }
                        }

                        // Tanggal lahir -> input type="date" (perlu format yyyy-mm-dd)
                        if (f.tanggal_lahir) {
                            const parts = f.tanggal_lahir.split('-');
                            if (parts.length === 3) {
                                // OCR returns dd-mm-yyyy, date input needs yyyy-mm-dd
                                const formatted = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');
                                const tglEl = document.getElementById('tanggal_lahir');
                                if (tglEl) tglEl.value = formatted;
                            }
                        }

                        // Select: Jenis Kelamin
                        if (f.jenis_kelamin) {
                            const jkEl = document.getElementById('jenis_kelamin');
                            if (jkEl) {
                                const jkVal = f.jenis_kelamin.toLowerCase().replace(/\s+/g, '-');
                                // Match: "LAKI-LAKI" -> "laki-laki", "PEREMPUAN" -> "perempuan"
                                for (const opt of jkEl.options) {
                                    if (opt.value === jkVal) {
                                        jkEl.value = jkVal;
                                        break;
                                    }
                                }
                            }
                        }

                        // Select: Agama
                        if (f.agama) {
                            const agamaEl = document.getElementById('agama');
                            if (agamaEl) {
                                const agamaVal = f.agama.toLowerCase();
                                const agamaMap = {
                                    'islam': 'islam',
                                    'kristen': 'kristen',
                                    'katolik': 'katolik',
                                    'hindu': 'hindu',
                                    'buddha': 'budha',
                                    'budha': 'budha',
                                    'konghucu': 'konhucu',
                                    'kong hu cu': 'konhucu',
                                };
                                const mapped = agamaMap[agamaVal] || agamaVal;
                                for (const opt of agamaEl.options) {
                                    if (opt.value === mapped) {
                                        agamaEl.value = mapped;
                                        break;
                                    }
                                }
                            }
                        }

                        // Select: Status Perkawinan
                        if (f.status_perkawinan) {
                            const spEl = document.getElementById('status_perkawinan');
                            if (spEl) {
                                const spVal = f.status_perkawinan.toLowerCase().replace(/\s+/g, '_');
                                // Match: "BELUM KAWIN" -> "belum_kawin", "KAWIN" -> "kawin"
                                for (const opt of spEl.options) {
                                    if (opt.value === spVal) {
                                        spEl.value = spVal;
                                        break;
                                    }
                                }
                            }
                        }

                        console.log('OCR auto-fill selesai:', f);
                    }

                    const successMessage = document.querySelector('.alert-success');
                    if (successMessage) {
                        successMessage.remove();
                    }

                    const messageBox = document.createElement('div');
                    messageBox.className = 'alert-success';
                    messageBox.textContent = data.message || 'Dokumen KTP dan KK berhasil diunggah.';
                    dokumenForm.parentElement.insertBefore(messageBox, dokumenForm);

                    setTimeout(() => {
                        identitasSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                })
                .catch((error) => {
                    alert(error.message);
                })
                .finally(() => {
                    btnLanjut.disabled = false;
                    btnLanjut.textContent = 'Lanjutkan';
                });
        });

        // Handle Batal Button
        btnBatal.addEventListener('click', function(e) {
            e.preventDefault();

            // Show upload form, hide identitas form
            uploadStep.classList.remove('hidden');
            identitasSection.classList.add('hidden');
            setIdentitasDisabled(true);

            // Smooth scroll to top
            setTimeout(() => {
                document.querySelector('.page-header').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        });

        if (btnSimpanIdentitas) {
            btnSimpanIdentitas.addEventListener('click', function() {
                const state = btnSimpanIdentitas.getAttribute('data-state');
                
                if (state === 'simpan') {
                    // Validasi data kosong
                    let hasEmpty = false;
                    let firstEmptyField = null;

                    identitasInputs.forEach(input => {
                        if (input.name && input.type !== 'hidden') {
                            if (!input.value.trim()) {
                                hasEmpty = true;
                                if (!firstEmptyField) firstEmptyField = input;
                                input.style.borderColor = 'red'; // Memberi tanda merah pada field yang kosong
                            } else {
                                input.style.borderColor = ''; // Menghapus tanda merah jika sudah diisi
                            }
                        }
                    });

                    if (hasEmpty) {
                        alert('⚠️ Tidak bisa menyimpan data. Semua data identitas harus diisi terlebih dahulu!');
                        if (firstEmptyField) {
                            firstEmptyField.focus();
                            firstEmptyField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return;
                    }

                    // Kumpulkan data
                    const payload = new FormData();
                    payload.append('_token', dokumenForm.querySelector('input[name="_token"]').value);
                    
                    identitasInputs.forEach(input => {
                        if (input.name && input.value !== undefined) {
                            payload.append(input.name, input.value);
                        }
                    });

                    btnSimpanIdentitas.disabled = true;
                    btnSimpanIdentitas.textContent = 'Menyimpan...';

                    fetch('{{ route("warga.pengurusan.simpan-identitas") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: payload,
                    })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) throw new Error(data.message || 'Gagal menyimpan data.');
                        return data;
                    })
                    .then((data) => {
                        // Tampilkan success message
                        const successMessage = document.querySelector('.alert-success');
                        if (successMessage) successMessage.remove();
                        const messageBox = document.createElement('div');
                        messageBox.className = 'alert-success';
                        messageBox.textContent = data.message || 'Data identitas berhasil disimpan.';
                        dokumenForm.parentElement.insertBefore(messageBox, dokumenForm);

                        // Ubah tombol jadi Kirim Pengajuan
                        btnSimpanIdentitas.setAttribute('data-state', 'kirim');
                        btnSimpanIdentitas.textContent = 'Kirim Pengajuan';
                        btnSimpanIdentitas.classList.add('btn-success'); // Opsional: tambah class styling
                    })
                    .catch((error) => {
                        alert(error.message);
                    })
                    .finally(() => {
                        btnSimpanIdentitas.disabled = false;
                        if (btnSimpanIdentitas.getAttribute('data-state') === 'simpan') {
                            btnSimpanIdentitas.textContent = 'Simpan Data';
                        }
                    });

                } else if (state === 'kirim') {
                    alert('Bagian pengajuan (Kirim Pengajuan final) akan dihubungkan pada controller berikutnya.');
                }
            });
        }

        function openPreviewModal({ url, name, type }) {
            previewModalTitle.textContent = name;

            previewModalImage.classList.add('hidden');
            previewModalPdf.classList.add('hidden');

            if (type === 'image') {
                previewModalImage.src = url;
                previewModalImage.classList.remove('hidden');
            } else {
                previewModalPdf.src = url;
                previewModalPdf.classList.remove('hidden');
            }

            previewModal.classList.remove('hidden');
        }

        function closePreviewModal() {
            previewModal.classList.add('hidden');
            previewModalImage.src = '';
            previewModalPdf.src = '';
        }

        previewButtons.forEach((button) => {
            button.addEventListener('click', function() {
                openPreviewModal({
                    url: this.dataset.previewUrl,
                    name: this.dataset.previewName,
                    type: this.dataset.previewType,
                });
            });
        });

        previewModalOverlay.addEventListener('click', closePreviewModal);
        previewModalClose.addEventListener('click', closePreviewModal);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !previewModal.classList.contains('hidden')) {
                closePreviewModal();
            }
        });
    </script>
</body>
</html>
