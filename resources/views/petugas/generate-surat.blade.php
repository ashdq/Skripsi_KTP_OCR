<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview & Edit Surat - Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ========================================
           GENERATE SURAT PAGE STYLES
           ======================================== */
        .generate-wrapper {
            background: #f0f2f5;
            padding: 2rem;
            flex: 1;
        }

        .generate-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .generate-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a472a;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .generate-header h1 i {
            font-size: 1.2rem;
            color: #4a90d9;
        }

        /* Toolbar */
        .editor-toolbar {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.85rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .toolbar-left {
            display: flex;
            gap: 0.6rem;
        }

        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.1rem;
            border: none;
            border-radius: 7px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-edit-mode {
            background: #f5a623;
            color: #fff;
        }

        .btn-edit-mode:hover {
            background: #e09416;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(245, 166, 35, 0.4);
        }

        .btn-edit-mode.active {
            background: #e09416;
            box-shadow: 0 3px 10px rgba(245, 166, 35, 0.4);
        }

        .btn-copy {
            background: #1abc9c;
            color: #fff;
        }

        .btn-copy:hover {
            background: #16a085;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(26, 188, 156, 0.4);
        }

        .btn-mode-view {
            background: #6c757d;
            color: #fff;
            font-size: 0.85rem;
        }

        .btn-mode-view:hover {
            background: #5a6268;
        }

        .btn-finalize {
            background: #1a472a;
            color: #fff;
        }

        .btn-finalize:hover {
            background: #133620;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(26, 71, 42, 0.4);
        }

        .btn-pdf-download {
            background: #e74c3c;
            color: #fff;
        }

        .btn-pdf-download:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        /* Petunjuk bar */
        .petunjuk-bar {
            background: #4a4a4a;
            color: #fff;
            border-radius: 8px;
            padding: 0.7rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .petunjuk-bar i {
            color: #ffd700;
            flex-shrink: 0;
        }

        /* Document Preview Area */
        .document-preview-container {
            background: #d0d5dc;
            border-radius: 10px;
            padding: 2rem;
            display: flex;
            justify-content: center;
            min-height: 600px;
        }

        .document-page {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            padding: 1.5cm 2cm 2cm 2cm;
            box-shadow: 0 6px 30px rgba(0,0,0,0.18);
            border-radius: 2px;
            position: relative;
            font-family: 'Times New Roman', Times, serif;
        }

        /* Edit mode styles */
        .document-page.edit-mode [contenteditable="true"] {
            outline: 2px dashed #f5a623;
            outline-offset: 2px;
            border-radius: 3px;
            cursor: text;
            min-width: 20px;
            min-height: 1em;
        }

        .document-page.edit-mode [contenteditable="true"]:focus {
            outline: 2px solid #f5a623;
            background: #fffdf0;
        }

        /* ========================================
           SURAT KETERANGAN TEMPLATE STYLES
           ======================================== */
        .surat-kop {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            padding-bottom: 0.6rem;
            border-bottom: 3px solid #000;
            margin-bottom: 0.8rem;
        }

        .surat-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .surat-logo-placeholder {
            width: 80px;
            height: 80px;
            border: 2px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #1a5276, #2980b9, #7fb3d6);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
            padding: 6px;
        }

        .surat-kop-text {
            flex: 1;
            text-align: center;
        }

        .surat-kop-text .kop-line-1 {
            font-size: 0.9rem;
            font-weight: normal;
            letter-spacing: 0.3px;
        }

        .surat-kop-text .kop-line-2 {
            font-size: 1rem;
            font-weight: normal;
            letter-spacing: 0.3px;
        }

        .surat-kop-text .kop-line-3 {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .surat-kop-text .kop-line-4 {
            font-size: 0.75rem;
            color: #333;
            margin-top: 3px;
        }

        .surat-kop-text .kop-line-5 {
            font-size: 0.75rem;
            color: #333;
        }

        .surat-title-section {
            text-align: center;
            margin: 1rem 0 0.5rem 0;
        }

        .surat-title {
            font-size: 1.1rem;
            font-weight: 900;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .surat-nomor {
            font-size: 0.85rem;
            font-weight: normal;
            margin-top: 4px;
        }

        .surat-pembuka {
            font-size: 0.9rem;
            margin: 1rem 0 0.5rem 0;
            text-align: justify;
            line-height: 1.8;
        }

        .surat-data-table {
            margin: 0.5rem 0 0.5rem 1rem;
            font-size: 0.9rem;
            line-height: 1.9;
        }

        .surat-data-table tr td:first-child {
            width: 130px;
            vertical-align: top;
            padding-right: 5px;
        }

        .surat-data-table tr td:nth-child(2) {
            width: 15px;
            vertical-align: top;
            padding-right: 5px;
        }

        .surat-isi {
            font-size: 0.9rem;
            margin: 1rem 0;
            text-align: justify;
            line-height: 1.8;
        }

        .surat-penutup {
            font-size: 0.9rem;
            margin: 1rem 0;
            text-align: justify;
            line-height: 1.8;
        }

        .surat-ttd {
            margin-top: 2rem;
            text-align: right;
            padding-right: 1rem;
            font-size: 0.9rem;
        }

        .surat-ttd-tempat {
            margin-bottom: 0.3rem;
        }

        .surat-ttd-jabatan {
            margin-bottom: 0.3rem;
        }

        .surat-ttd-space {
            height: 70px;
        }

        .surat-ttd-nama {
            font-weight: 900;
            text-decoration: underline;
            font-size: 0.95rem;
        }

        .surat-ttd-nip {
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .surat-qr-area {
            position: absolute;
            bottom: 2cm;
            left: 2cm;
            font-size: 0.7rem;
            color: #555;
            text-align: center;
        }

        .qr-placeholder {
            width: 70px;
            height: 70px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            color: #999;
            margin: 0 auto 4px;
        }

        /* Alert Notifications */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 600;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success-banner {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error-banner {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Finalize modal */
        .finalize-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
            z-index: 9000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .finalize-overlay.show {
            display: flex;
        }

        .finalize-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            width: min(460px, 90vw);
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            text-align: center;
        }

        .finalize-icon {
            font-size: 3rem;
            color: #1a472a;
            margin-bottom: 1rem;
        }

        .finalize-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a472a;
            margin-bottom: 0.5rem;
        }

        .finalize-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .finalize-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-confirm {
            padding: 0.75rem 1.5rem;
            background: #1a472a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }

        .btn-confirm:hover { background: #133620; }

        .btn-cancel-modal {
            padding: 0.75rem 1.5rem;
            background: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }

        .btn-cancel-modal:hover { background: #d1d5db; }

        /* Responsive */
        @media (max-width: 900px) {
            .document-page {
                width: 100%;
                min-height: auto;
                padding: 1rem;
            }
            .generate-wrapper {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="warga-home-page">
    @include('layout.header')

    <div class="main-container">
        @include('layout.petugas-sidebar')

        <main class="generate-wrapper">
            {{-- Page Header --}}
            <div class="generate-header">
                <a href="{{ route('petugas.daftar') }}" style="color:#1a472a; text-decoration:none; font-size:1.3rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>
                    <i class="fas fa-file-medical"></i>
                    Generate & Edit Surat
                </h1>
            </div>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="alert-banner alert-success-banner">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-banner alert-error-banner">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Editor Toolbar --}}
            <div class="editor-toolbar">
                <div class="toolbar-left">
                    <button id="btn-aktifkan-edit" class="btn-toolbar btn-edit-mode" onclick="toggleEditMode()">
                        <i class="fas fa-pen"></i> Aktifkan Edit
                    </button>
                </div>
                <div class="toolbar-right" style="display:flex; gap:0.6rem;">
                    <button type="button" class="btn-toolbar btn-finalize" onclick="submitSimpan()" style="background:linear-gradient(135deg,#1a472a,#2e7d52);">
                        <i class="fas fa-save"></i> Simpan Surat
                    </button>
                </div>
            </div>

            {{-- Petunjuk Bar --}}
            <div class="petunjuk-bar" id="petunjuk-bar">
                <i class="fas fa-info-circle"></i>
                <span><strong>Petunjuk:</strong> Klik tombol "Aktifkan Edit" untuk mengedit teks langsung pada preview. Klik bagian teks yang ingin diubah lalu ketik.</span>
            </div>

            @if($surat->keterangan)
            <div class="petunjuk-bar" style="background: #17a2b8; margin-top: -0.5rem;">
                <i class="fas fa-comment-dots" style="color: white;"></i>
                <span><strong>Keterangan dari Warga:</strong> {{ $surat->keterangan }}</span>
            </div>
            @endif

            {{-- Document Preview --}}
            <div class="document-preview-container" id="document-container">
                <div class="document-page" id="document-page">
                    @if($surat->html_content)
                        {!! $surat->html_content !!}
                    @else

                    {{-- KOP SURAT --}}
                    <table class="kop-table" style="width: 100%; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 15px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 100px; text-align: left; vertical-align: middle;">
                                <img src="{{ asset('img/logo-kab.png') }}" alt="Logo Kabupaten Blitar" style="width: 85px; height: 85px;">
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <div class="kop-line-1" contenteditable="false" style="font-size: 12pt; font-weight: normal;">PEMERINTAH KABUPATEN BLITAR</div>
                                <div class="kop-line-2" contenteditable="false" style="font-size: 12pt; font-weight: normal;">KECAMATAN TALUN</div>
                                <div class="kop-line-3" contenteditable="false" style="font-size: 18pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin: 2px 0;">KELURAHAN TALUN</div>
                                <div class="kop-line-4" contenteditable="false" style="font-size: 9pt;">Jalan Raya Talun Nomor 57 Kecamatan Talun Kode Pos 66183</div>
                                <div class="kop-line-5" contenteditable="false" style="font-size: 9pt;">Telp: (0342) 692 809 Email: kelurahantalun@example.com</div>
                            </td>
                        </tr>
                    </table>

                    {{-- JUDUL SURAT --}}
                    <div class="surat-title-section" style="text-align: center; margin: 15px 0;">
                        <div class="surat-title" contenteditable="false" style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 3px;">{{ strtoupper($surat->jenis_surat) }}</div>
                        <div class="surat-nomor" contenteditable="false" style="font-size: 11pt;">Nomor: <span id="nomor-surat">{{ strtoupper(substr(str_replace(' ', '', $surat->jenis_surat), 0, 3) . rand(100,999) . substr(str_replace([' ','Surat Keterangan '], ['','SK'], $surat->jenis_surat), 0, 1) . date('Y')) }}</span></div>
                    </div>

                    {{-- PEMBUKA --}}
                    <p class="surat-pembuka" contenteditable="false" style="margin: 15px 0 10px 0; text-align: justify;">Yang bertanda tangan di bawah ini, Lurah Talun, Kecamatan Talun, Kabupaten Blitar, menerangkan bahwa:</p>

                    {{-- DATA IDENTITAS --}}
                    <table class="surat-data-table" style="margin: 5px 0 15px 25px; font-size: 12pt; width: calc(100% - 25px); border-collapse: collapse;">
                        <tr>
                            <td style="width: 160px; padding: 3px 0; vertical-align: top;">Nama</td>
                            <td style="width: 20px; padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;"><strong>{{ strtoupper($surat->ocr->nama ?? '-') }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">NIK</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ $surat->ocr->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">Tempat, Tgl Lahir</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ $surat->ocr->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($surat->ocr->tanggal_lahir)->translatedFormat('d F Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">Jenis Kelamin</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ $surat->ocr->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">Agama</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ $surat->ocr->agama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">Pekerjaan</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ $surat->ocr->pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; vertical-align: top;">Alamat</td>
                            <td style="padding: 3px 0; vertical-align: top;">:</td>
                            <td contenteditable="false" style="padding: 3px 0; vertical-align: top;">{{ ($surat->ocr->alamat ?? '-') . ($surat->ocr->rt_rw ? ' RT ' . $surat->ocr->rt_rw : '') . ($surat->ocr->kelurahan ? ' Desa ' . $surat->ocr->kelurahan : '') }}</td>
                        </tr>
                    </table>

                    {{-- ISI SURAT --}}
                    <p class="surat-isi" contenteditable="false" style="margin: 15px 0; text-align: justify;">Berdasarkan data dan pengamatan Pemerintah Kelurahan Talun, yang bersangkutan benar berdomisili dan bertempat tinggal di wilayah Kelurahan Talun sampai dengan surat ini dibuat.</p>

                    <p class="surat-penutup" contenteditable="false" style="margin: 15px 0 25px 0; text-align: justify;">Surat keterangan ini dibuat untuk keperluan administrasi dan keperluan lain yang sah sesuai dengan peraturan yang berlaku.</p>

                    {{-- TANDA TANGAN --}}
                    <table class="ttd-table" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                        <tr>
                            <td style="vertical-align: top; width: 50%; padding-top: 40px;">
                                <div class="ttd-qr" style="text-align: center; font-size: 9pt; color: #555;">
                                    <div class="qr-box" style="width: 70px; height: 70px; border: 1px solid #ccc; margin: 0 auto 5px auto; line-height: 70px; text-align: center; color: #aaa; font-size: 8pt;">
                                        [QR CODE]
                                    </div>
                                    <span contenteditable="false">Scan untuk validasi</span>
                                </div>
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                <div class="ttd-right" style="text-align: center;">
                                    <p contenteditable="false" style="margin: 2px 0;" id="preview-lokasi-ttd">Talun, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                    <p contenteditable="false" style="margin: 2px 0;">Kepala Kelurahan Talun</p>
                                    <div class="ttd-space" id="preview-ttd-space" style="height: 80px; margin: 10px 0;"></div>
                                    <p contenteditable="false" style="margin: 2px 0; font-weight: bold; text-decoration: underline;" id="preview-nama-petugas">Nama Kepala Lurah</p>
                                    <p contenteditable="false" style="margin: 2px 0;" id="preview-nip-petugas">NIP. -</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('layout.footer')



    <form id="simpan-form" action="{{ route('petugas.pengajuan.simpan', $surat->id) }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="html_content" id="html-content-input">
    </form>

    <script>
        let editModeActive = false;

        function toggleEditMode() {
            editModeActive = !editModeActive;
            const page = document.getElementById('document-page');
            const btn = document.getElementById('btn-aktifkan-edit');
            const petunjuk = document.getElementById('petunjuk-bar');

            if (editModeActive) {
                page.classList.add('edit-mode');
                // Enable all contenteditable elements
                page.querySelectorAll('[contenteditable]').forEach(el => {
                    el.setAttribute('contenteditable', 'true');
                });
                btn.innerHTML = '<i class="fas fa-times"></i> Nonaktifkan Edit';
                btn.style.background = '#e74c3c';
                petunjuk.style.background = '#1a472a';
            } else {
                page.classList.remove('edit-mode');
                page.querySelectorAll('[contenteditable]').forEach(el => {
                    el.setAttribute('contenteditable', 'false');
                });
                btn.innerHTML = '<i class="fas fa-pen"></i> Aktifkan Edit';
                btn.style.background = '#f5a623';
                petunjuk.style.background = '#4a4a4a';
            }
        }

        // Fungsi salinSemua dihapus karena tombolnya dihilangkan

        function submitSimpan() {
            const page = document.getElementById('document-page');
            
            // Nonaktifkan mode edit jika sedang aktif agar elemen contenteditable tidak tersimpan dalam state aktif
            if (editModeActive) {
                toggleEditMode();
            }

            document.getElementById('html-content-input').value = page.innerHTML;
            document.getElementById('simpan-form').submit();
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed; top: 80px; right: 20px; z-index: 99999;
                padding: 0.875rem 1.25rem; border-radius: 10px; font-weight: 600;
                font-family: 'Segoe UI', sans-serif; font-size: 0.9rem;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                display: flex; align-items: center; gap: 0.5rem;
                animation: slideIn 0.3s ease;
                ${type === 'success' ? 'background:#1a472a; color:#fff;' : 'background:#e74c3c; color:#fff;'}
            `;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.4s'; }, 2500);
            setTimeout(() => { toast.remove(); }, 3000);
        }

        // Print style - hide toolbar when printing
        window.addEventListener('beforeprint', () => {
            document.querySelector('.generate-header').style.display = 'none';
            document.querySelector('.editor-toolbar').style.display = 'none';
            document.querySelector('.petunjuk-bar').style.display = 'none';
            document.querySelector('.document-preview-container').style.background = 'none';
            document.querySelector('.document-preview-container').style.padding = '0';
        });
        window.addEventListener('afterprint', () => {
            document.querySelector('.generate-header').style.display = '';
            document.querySelector('.editor-toolbar').style.display = '';
            document.querySelector('.petunjuk-bar').style.display = '';
            document.querySelector('.document-preview-container').style.background = '';
            document.querySelector('.document-preview-container').style.padding = '';
        });
    </script>
</body>
</html>
