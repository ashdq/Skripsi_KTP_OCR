<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ============================================
           TANDA TANGAN SURAT - PAGE STYLES
           ============================================ */
        .ttd-wrapper {
            background: #f0f2f5;
            padding: 2rem;
            flex: 1;
        }

        .ttd-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .ttd-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a472a;
        }

        /* ---- Sections ---- */
        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e0e6ed;
            margin-bottom: 1.25rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-card-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #f8f9fa, #f0f2f5);
            border-bottom: 1px solid #e0e6ed;
            font-weight: 700;
            color: #1a472a;
            font-size: 0.95rem;
        }

        .section-card-body {
            padding: 1.25rem;
        }

        /* ---- Signature Pad ---- */
        .sig-pad-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .sig-pad-wrap {
            position: relative;
            width: 100%;
            max-width: 420px;
            border: 2px dashed #c0ccd8;
            border-radius: 10px;
            background: #fafafa;
            overflow: hidden;
        }

        #sig-canvas {
            display: block;
            width: 100%;
            height: 180px;
            cursor: crosshair;
            touch-action: none;
        }

        .sig-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #bcc8d4;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .sig-placeholder i { font-size: 2rem; margin-bottom: 0.4rem; }
        .sig-placeholder span { font-size: 0.85rem; }

        .sig-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .btn-clear-sig {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            background: #f0f2f5;
            border: 1px solid #d0d7de;
            border-radius: 7px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }

        .btn-clear-sig:hover { background: #e2e6ea; }

        /* ---- Preview TTD Layout (seperti gambar referensi) ---- */
        .preview-ttd-box {
            border: 2px dashed #c0ccd8;
            border-radius: 10px;
            background: #fafffe;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 2rem;
            min-height: 140px;
        }

        /* Kiri: QR Code area */
        .preview-ttd-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
        }

        .qr-box {
            width: 80px;
            height: 80px;
            background: #f0f2f5;
            border: 1px solid #c0ccd8;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #888;
        }

        .qr-label {
            font-size: 0.72rem;
            color: #888;
            text-align: center;
        }

        /* Kanan: Tempat TTD */
        .preview-ttd-right {
            text-align: center;
            min-width: 180px;
        }

        .preview-ttd-kota-tanggal {
            font-size: 0.88rem;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .preview-ttd-jabatan {
            font-size: 0.88rem;
            margin-bottom: 0.75rem;
            color: #333;
        }

        .preview-ttd-img-wrap {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.4rem;
        }

        #preview-sig-img {
            max-height: 60px;
            max-width: 160px;
            display: none;
        }

        .preview-ttd-nama {
            font-weight: 700;
            text-decoration: underline;
            font-size: 0.9rem;
        }

        /* ---- Info box ---- */
        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: #e8f4fd;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #2c6f99;
        }

        .info-box i { flex-shrink: 0; margin-top: 2px; }

        /* ---- Form fields ---- */
        .form-row {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-row label {
            font-weight: 700;
            font-size: 0.875rem;
            color: #333;
        }

        .form-row input {
            padding: 0.6rem 0.9rem;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            font-size: 0.9rem;
            font-family: inherit;
            width: 100%;
            max-width: 280px;
            transition: border-color 0.2s;
        }

        .form-row input:focus {
            outline: none;
            border-color: #1a472a;
        }

        .form-hint {
            font-size: 0.78rem;
            color: #888;
        }

        /* ---- Action Buttons ---- */
        .ttd-submit-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-ttd-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.6rem;
            background: linear-gradient(135deg, #1a472a, #2e7d52);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }

        .btn-ttd-submit:hover {
            background: linear-gradient(135deg, #133620, #245e3e);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(26,71,42,0.3);
        }

        .btn-ttd-submit:disabled {
            background: #aaa;
            cursor: not-allowed;
            transform: none;
        }

        .btn-ttd-batal {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.3rem;
            background: #f0f2f5;
            border: 1px solid #ccc;
            border-radius: 9px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            color: #444;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-ttd-batal:hover { background: #e2e6ea; color: #333; }

        /* ---- Flash messages ---- */
        .alert-bar {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .alert-bar-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .alert-bar-error   { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

        /* ---- Info surat ---- */
        .surat-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            background: #e8f5e9;
            color: #1a472a;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .surat-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .surat-meta-item { display: flex; flex-direction: column; gap: 2px; }
        .surat-meta-label { font-size: 0.75rem; color: #888; font-weight: 600; }
        .surat-meta-value { font-size: 0.9rem; font-weight: 700; color: #1a2a1a; }
    </style>
</head>
<body class="warga-home-page">
    @include('layout.header')

    <div class="main-container">
        @include('layout.petugas-sidebar')

        <main class="ttd-wrapper">
            {{-- Header --}}
            <div class="ttd-header">
                <a href="{{ route('petugas.tanda-tangan') }}" style="color:#1a472a; text-decoration:none; font-size:1.3rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1><i class="fas fa-signature"></i> Tanda Tangan Surat</h1>
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="alert-bar alert-bar-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-bar alert-bar-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Info Surat --}}
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fas fa-file-alt"></i> Informasi Surat
                </div>
                <div class="section-card-body">
                    <div class="surat-info-badge">
                        <i class="fas fa-tag"></i> {{ $surat->jenis_surat }}
                    </div>
                    <div class="surat-meta-grid">
                        <div class="surat-meta-item">
                            <span class="surat-meta-label">Nama Pemohon</span>
                            <span class="surat-meta-value">{{ $surat->ocr->nama ?? '-' }}</span>
                        </div>
                        <div class="surat-meta-item">
                            <span class="surat-meta-label">NIK</span>
                            <span class="surat-meta-value" style="font-family:monospace;">{{ $surat->ocr->nik ?? '-' }}</span>
                        </div>
                        <div class="surat-meta-item">
                            <span class="surat-meta-label">Tanggal Pengajuan</span>
                            <span class="surat-meta-value">{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="surat-meta-item">
                            <span class="surat-meta-label">Status</span>
                            <span class="surat-meta-value" style="color:#856404;">⏳ Menunggu Tanda Tangan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PREVIEW SURAT --}}
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fas fa-file-invoice"></i> Preview Surat
                </div>
                <div style="background:#d0d5dc; padding:1.5rem; border-radius:0 0 12px 12px;">
                    <div class="letter-preview-scroll">
                        <div class="letter-page">

                            {{-- KOP SURAT --}}
                            <div style="display:flex; align-items:center; gap:1.2rem; padding-bottom:0.6rem; border-bottom:3px solid #000; margin-bottom:0.8rem;">
                                <img src="{{ asset('img/logo-kab.png') }}" alt="Logo Kabupaten" style="width:80px; height:80px; object-fit:contain; flex-shrink:0;">
                                <div style="flex:1; text-align:center;">
                                    <div style="font-size:0.9rem;">PEMERINTAH KABUPATEN BLITAR</div>
                                    <div style="font-size:1rem;">KECAMATAN TALUN</div>
                                    <div style="font-size:1.5rem; font-weight:900; letter-spacing:1px; text-transform:uppercase;">KELURAHAN TALUN</div>
                                    <div style="font-size:0.75rem; color:#333; margin-top:3px;">Jalan Raya Talun Nomor 57 Kecamatan Talun Kode Pos 66183</div>
                                    <div style="font-size:0.75rem; color:#333;">Telp: (0342) 692 809 Email: kelurahantalun@example.com</div>
                                </div>
                            </div>

                            {{-- JUDUL SURAT --}}
                            <div style="text-align:center; margin:1rem 0 0.5rem 0;">
                                <div style="font-size:1.1rem; font-weight:900; text-decoration:underline; text-transform:uppercase; letter-spacing:1px;">{{ strtoupper($surat->jenis_surat) }}</div>
                                <div style="font-size:0.85rem; margin-top:4px;">Nomor: {{ strtoupper(substr(str_replace(' ','',$surat->jenis_surat),0,3)) }}/{{ $surat->id }}/KEL-TLN/{{ date('Y') }}</div>
                            </div>

                            {{-- PEMBUKA --}}
                            <p style="margin:1rem 0 0.5rem 0; text-align:justify;">Yang bertanda tangan di bawah ini, Lurah Talun, Kecamatan Talun, Kabupaten Blitar, menerangkan bahwa:</p>

                            {{-- DATA IDENTITAS --}}
                            <table style="margin:0.5rem 0 0.5rem 1rem; line-height:1.9;">
                                <tr>
                                    <td style="width:145px; vertical-align:top;">Nama</td>
                                    <td style="width:15px; vertical-align:top;">:</td>
                                    <td><strong>{{ strtoupper($surat->ocr->nama ?? '-') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>NIK</td><td>:</td>
                                    <td>{{ $surat->ocr->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tgl Lahir</td><td>:</td>
                                    <td>{{ $surat->ocr->tempat_lahir ?? '-' }}, {{ $surat->ocr->tanggal_lahir ? \Carbon\Carbon::parse($surat->ocr->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td><td>:</td>
                                    <td>{{ ucfirst($surat->ocr->jenis_kelamin ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <td>Agama</td><td>:</td>
                                    <td>{{ ucfirst($surat->ocr->agama ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan</td><td>:</td>
                                    <td>{{ $surat->ocr->pekerjaan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td><td>:</td>
                                    <td>{{ ($surat->ocr->alamat ?? '-') . ($surat->ocr->rt_rw ? ' RT '.$surat->ocr->rt_rw : '') . ($surat->ocr->kelurahan ? ' Desa '.$surat->ocr->kelurahan : '') }}</td>
                                </tr>
                            </table>

                            {{-- ISI --}}
                            <p style="margin:1rem 0; text-align:justify;">Berdasarkan data dan pengamatan Pemerintah Kelurahan Talun, yang bersangkutan benar berdomisili dan bertempat tinggal di wilayah Kelurahan Talun sampai dengan surat ini dibuat.</p>
                            <p style="margin:1rem 0; text-align:justify;">Surat keterangan ini dibuat untuk keperluan administrasi dan keperluan lain yang sah sesuai dengan peraturan yang berlaku.</p>

                            {{-- TTD placeholder --}}
                            <div style="margin-top:2rem; text-align:right; padding-right:1rem;">
                                <div>Talun, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                                <div style="margin-bottom:0.3rem;">Kepala Kelurahan Talun</div>
                                <div style="height:65px; display:flex; align-items:center; justify-content:flex-end;">
                                    <em style="font-size:0.78rem; color:#bbb;">[Tanda tangan akan ditambahkan]</em>
                                </div>
                                <div style="font-weight:900; text-decoration:underline;" id="preview-nama-petugas">{{ $surat->petugas->nama ?? 'Nama Kepala Lurah' }}</div>
                                <div style="font-size:0.85rem; margin-top:2px;" id="preview-nip-petugas">NIP. -</div>
                            </div>

                            {{-- QR placeholder --}}
                            <div style="position:absolute; bottom:1.5cm; left:2cm; text-align:center; font-size:0.7rem; color:#555;">
                                <div style="width:60px; height:60px; background:#f0f0f0; border:1px solid #ccc; display:flex; align-items:center; justify-content:center; margin:0 auto 4px;">
                                    <i class="fas fa-qrcode" style="font-size:1.8rem; color:#bbb;"></i>
                                </div>
                                <span>Scan untuk validasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tanda Tangan Digital --}}
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fas fa-pen-nib"></i> Tanda Tangan Digital
                </div>
                <div class="section-card-body">
                    <p style="font-size:0.88rem; color:#666; margin-bottom:1rem;">Buat tanda tangan Anda di kolom berikut. Tanda tangan akan diterapkan pada dokumen PDF surat.</p>
                    <div class="sig-pad-container">
                        <div class="sig-pad-wrap" id="sig-pad-wrap">
                            <canvas id="sig-canvas" width="800" height="360"></canvas>
                            <div class="sig-placeholder" id="sig-placeholder">
                                <i class="fas fa-signature"></i>
                                <span>Tanda tangan di sini</span>
                            </div>
                        </div>
                        <div class="sig-actions">
                            <button type="button" class="btn-clear-sig" onclick="clearSignature()">
                                <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Konfirmasi Tanda Tangan --}}
            <div class="section-card">
                <div class="section-card-header" style="background:linear-gradient(135deg,#e8f5e9,#d4edda); color:#1a472a;">
                    <i class="fas fa-pen"></i> Konfirmasi Tanda Tangan
                </div>
                <div class="section-card-body">
                    <form action="{{ route('petugas.pengajuan.tandatangan', $surat->id) }}" method="POST" id="ttd-form">
                        @csrf
                        <input type="hidden" name="signature_data" id="signature-data-input">

                        <div class="form-row" style="margin-bottom: 1rem;">
                            <label for="nama_petugas">Nama Petugas Penandatangan</label>
                            <input type="text" id="nama_petugas" name="nama_petugas" value="{{ $surat->petugas->nama ?? 'Petugas Contoh' }}" placeholder="Contoh: Budi Santoso, S.Sos">
                        </div>

                        <div class="form-row" style="margin-bottom: 1.5rem;">
                            <label for="nip_petugas">NIP Petugas</label>
                            <input type="text" id="nip_petugas" name="nip_petugas" value="-" placeholder="Masukkan NIP jika ada">
                        </div>


                        <div class="ttd-submit-bar">
                            <button type="submit" class="btn-ttd-submit" id="btn-ttd-submit" onclick="return prepareSubmit()">
                                <i class="fas fa-pen-fancy"></i> Tanda Tangani & Generate PDF
                            </button>
                            <a href="{{ route('petugas.tanda-tangan') }}" class="btn-ttd-batal">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')

    <script>
        // =============================================
        //  Signature Pad
        // =============================================
        const canvas = document.getElementById('sig-canvas');
        const ctx    = canvas.getContext('2d');
        const placeholder = document.getElementById('sig-placeholder');

        // Setup canvas resolution
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width  = rect.width  * window.devicePixelRatio;
            canvas.height = rect.height * window.devicePixelRatio;
            ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
            ctx.strokeStyle = '#1a1a1a';
            ctx.lineWidth   = 2.5;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        let isDrawing = false;
        let hasSig = false;
        let lastX = 0, lastY = 0;

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            if (e.touches) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top,
                };
            }
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            const p = getPos(e);
            lastX = p.x; lastY = p.y;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDrawing) return;
            const p = getPos(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            lastX = p.x; lastY = p.y;
            if (!hasSig) {
                hasSig = true;
                placeholder.style.display = 'none';
            }
        });

        canvas.addEventListener('mouseup', () => { isDrawing = false; });
        canvas.addEventListener('mouseleave', () => { isDrawing = false; });

        // Touch support
        canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            isDrawing = true;
            const p = getPos(e);
            lastX = p.x; lastY = p.y;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
        }, { passive: false });

        canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            if (!isDrawing) return;
            const p = getPos(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            lastX = p.x; lastY = p.y;
            if (!hasSig) {
                hasSig = true;
                placeholder.style.display = 'none';
            }
        }, { passive: false });

        canvas.addEventListener('touchend', () => { isDrawing = false; });

        function clearSignature() {
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width, rect.height);
            hasSig = false;
            placeholder.style.display = '';
        }



        function prepareSubmit() {
            if (!hasSig) {
                alert('⚠️ Silakan buat tanda tangan terlebih dahulu sebelum melanjutkan.');
                return false;
            }
            document.getElementById('signature-data-input').value = canvas.toDataURL('image/png');
            return true;
        }

        // Live preview update
        const inputNama = document.getElementById('nama_petugas');
        const inputNip = document.getElementById('nip_petugas');
        const previewNama = document.getElementById('preview-nama-petugas');
        const previewNip = document.getElementById('preview-nip-petugas');

        if(inputNama && previewNama) {
            inputNama.addEventListener('input', function() {
                previewNama.innerText = this.value || 'Nama Kepala Lurah';
            });
            // trigger on load
            previewNama.innerText = inputNama.value || 'Nama Kepala Lurah';
        }

        if(inputNip && previewNip) {
            inputNip.addEventListener('input', function() {
                previewNip.innerText = 'NIP. ' + (this.value || '-');
            });
            // trigger on load
            previewNip.innerText = 'NIP. ' + (inputNip.value || '-');
        }
    </script>
</body>
</html>
