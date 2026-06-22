<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="warga-home-page">
    @include('layout.header')
    
    <div class="main-container">
        @include('layout.petugas-sidebar')
        
        <main class="main-content">
            <div class="content-wrapper">
                <div class="page-header" style="display:flex; align-items:center; gap:1rem;">
                    <a href="{{ route('petugas.daftar') }}" style="color:#1a472a; text-decoration:none; font-size:1.5rem;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1>Detail Pengajuan Surat</h1>
                </div>

                <div class="form-section">
                    <div class="identitas-section" style="display: block;">
                        <div class="identitas-header">
                            <h2>Data Identitas Pemohon</h2>
                            <p>Jenis Surat: <strong>{{ $surat->jenis_surat }}</strong></p>
                        </div>

                        <!-- Grid Data Identitas -->
                        <div class="identitas-grid">
                            <!-- Nama Lengkap -->
                            <div class="form-group">
                                <label for="nama" class="form-label">
                                    <i class="fas fa-user"></i> Nama Lengkap
                                </label>
                                <input type="text" id="nama" class="form-input" value="{{ $surat->ocr->nama ?? '-' }}" disabled>
                            </div>

                            <!-- NIK -->
                            <div class="form-group">
                                <label for="nik" class="form-label">
                                    <i class="fas fa-id-card"></i> Nomor NIK
                                </label>
                                <input type="text" id="nik" class="form-input" value="{{ $surat->ocr->nik ?? '-' }}" disabled>
                            </div>

                            <!-- Nomor KK -->
                            <div class="form-group">
                                <label for="nomor_kk" class="form-label">
                                    <i class="fas fa-users"></i> Nomor Kartu Keluarga
                                </label>
                                <input type="text" id="nomor_kk" class="form-input" value="{{ $surat->ocr->nomor_kk ?? '-' }}" disabled>
                            </div>

                            <!-- Tempat Lahir -->
                            <div class="form-group">
                                <label for="tempat_lahir" class="form-label">
                                    <i class="fas fa-map-marker"></i> Tempat Lahir
                                </label>
                                <input type="text" id="tempat_lahir" class="form-input" value="{{ $surat->ocr->tempat_lahir ?? '-' }}" disabled>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="form-group">
                                <label for="tanggal_lahir" class="form-label">
                                    <i class="fas fa-calendar"></i> Tanggal Lahir
                                </label>
                                <input type="text" id="tanggal_lahir" class="form-input" value="{{ $surat->ocr->tanggal_lahir ? \Carbon\Carbon::parse($surat->ocr->tanggal_lahir)->translatedFormat('d F Y') : '-' }}" disabled>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="form-group">
                                <label for="jenis_kelamin" class="form-label">
                                    <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                </label>
                                <input type="text" id="jenis_kelamin" class="form-input" value="{{ ucfirst($surat->ocr->jenis_kelamin ?? '-') }}" disabled>
                            </div>

                            <!-- Agama -->
                            <div class="form-group">
                                <label for="agama" class="form-label">
                                    <i class="fas fa-heart"></i> Agama
                                </label>
                                <input type="text" id="agama" class="form-input" value="{{ ucfirst($surat->ocr->agama ?? '-') }}" disabled>
                            </div>

                            <!-- Status Perkawinan -->
                            <div class="form-group">
                                <label for="status_perkawinan" class="form-label">
                                    <i class="fas fa-ring"></i> Status Perkawinan
                                </label>
                                <input type="text" id="status_perkawinan" class="form-input" value="{{ ucwords(str_replace('_', ' ', $surat->ocr->status_perkawinan ?? '-')) }}" disabled>
                            </div>

                            <!-- Pekerjaan -->
                            <div class="form-group">
                                <label for="pekerjaan" class="form-label">
                                    <i class="fas fa-briefcase"></i> Pekerjaan
                                </label>
                                <input type="text" id="pekerjaan" class="form-input" value="{{ $surat->ocr->pekerjaan ?? '-' }}" disabled>
                            </div>

                            <!-- Alamat -->
                            <div class="form-group form-group-full">
                                <label for="alamat" class="form-label">
                                    <i class="fas fa-address-card"></i> Alamat Lengkap
                                </label>
                                <textarea id="alamat" class="form-input" rows="3" disabled>{{ $surat->ocr->alamat ?? '-' }}</textarea>
                            </div>

                            <!-- RT/RW -->
                            <div class="form-group">
                                <label for="rt" class="form-label">
                                    <i class="fas fa-home"></i> RT/RW
                                </label>
                                <input type="text" id="rt" class="form-input" value="{{ $surat->ocr->rt_rw ?? '-' }}" disabled>
                            </div>

                            <!-- Kelurahan/Desa -->
                            <div class="form-group">
                                <label for="kelurahan" class="form-label">
                                    <i class="fas fa-map"></i> Kelurahan/Desa
                                </label>
                                <input type="text" id="kelurahan" class="form-input" value="{{ $surat->ocr->kelurahan ?? '-' }}" disabled>
                            </div>

                            <!-- Kecamatan -->
                            <div class="form-group">
                                <label for="kecamatan" class="form-label">
                                    <i class="fas fa-map"></i> Kecamatan
                                </label>
                                <input type="text" id="kecamatan" class="form-input" value="{{ $surat->ocr->kecamatan ?? '-' }}" disabled>
                            </div>

                            <!-- Kota -->
                            <div class="form-group">
                                <label for="kota" class="form-label">
                                    <i class="fas fa-city"></i> Kota/Kabupaten
                                </label>
                                <input type="text" id="kota" class="form-input" value="{{ $surat->ocr->kota_kabupaten ?? '-' }}" disabled>
                            </div>

                            <!-- Provinsi -->
                            <div class="form-group">
                                <label for="provinsi" class="form-label">
                                    <i class="fas fa-map-location-dot"></i> Provinsi
                                </label>
                                <input type="text" id="provinsi" class="form-input" value="{{ $surat->ocr->provinsi ?? '-' }}" disabled>
                            </div>

                            <!-- No Telepon -->
                            <div class="form-group">
                                <label for="no_telepon" class="form-label">
                                    <i class="fas fa-phone"></i> No Telepon Pengaju
                                </label>
                                <input type="tel" id="no_telepon" class="form-input" value="{{ $surat->warga->nomor_hp ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="dokumen-section" style="margin-top: 2rem;">
                        <div class="dokumen-header" style="margin-bottom: 1.5rem;">
                            <h2 style="font-size: 1.25rem; font-weight: 600; color: #1a472a; margin-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">Dokumen Pendukung</h2>
                        </div>
                        
                        <div class="dokumen-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            @php $dokumen = $surat->warga->dokumen->first(); @endphp
                            
                            <div class="dokumen-card" style="background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                                <h3 style="font-size: 1rem; font-weight: 600; color: #4a5568; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-id-card"></i> Foto KTP
                                </h3>
                                <div class="image-container" style="display: flex; justify-content: center; align-items: center; min-height: 200px; background: #f8fafc; border-radius: 6px; overflow: hidden;">
                                    @if($dokumen && $dokumen->file_path_ktp)
                                        <img src="{{ asset('storage/' . $dokumen->file_path_ktp) }}" alt="Foto KTP" style="max-width: 100%; height: auto; object-fit: contain; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                    @else
                                        <div style="color: #64748b; text-align: center;">
                                            <i class="fas fa-image fa-3x" style="margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                            <p>File KTP tidak tersedia</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="dokumen-card" style="background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                                <h3 style="font-size: 1rem; font-weight: 600; color: #4a5568; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-users"></i> Foto Kartu Keluarga
                                </h3>
                                <div class="image-container" style="display: flex; justify-content: center; align-items: center; min-height: 200px; background: #f8fafc; border-radius: 6px; overflow: hidden;">
                                    @if($dokumen && $dokumen->file_path_kk)
                                        <img src="{{ asset('storage/' . $dokumen->file_path_kk) }}" alt="Foto KK" style="max-width: 100%; height: auto; object-fit: contain; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                    @else
                                        <div style="color: #64748b; text-align: center;">
                                            <i class="fas fa-image fa-3x" style="margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                            <p>File KK tidak tersedia</p>
                                        </div>
                                    @endif
                                </div>
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
