<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenis_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
        }

        .page {
            width: 100%;
            padding: 1.5cm 2cm 2cm 2cm;
        }

        /* KOP SURAT */
        .kop {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
            margin-bottom: 14px;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .l1 { font-size: 11pt; font-weight: normal; }
        .kop-text .l2 { font-size: 11pt; font-weight: normal; }
        .kop-text .l3 { font-size: 18pt; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; }
        .kop-text .l4 { font-size: 9pt; margin-top: 4px; }
        .kop-text .l5 { font-size: 9pt; }

        /* JUDUL */
        .judul {
            text-align: center;
            margin: 12px 0 6px 0;
        }

        .judul-title {
            font-size: 14pt;
            font-weight: 900;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .judul-nomor {
            font-size: 11pt;
            margin-top: 4px;
        }

        /* BODY */
        .pembuka {
            margin: 14px 0 6px 0;
            text-align: justify;
            font-size: 12pt;
        }

        .data-table {
            margin: 6px 0 10px 20px;
            font-size: 12pt;
            width: calc(100% - 20px);
            border-collapse: collapse;
        }

        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .data-table td:first-child {
            width: 145px;
        }

        .data-table td:nth-child(2) {
            width: 15px;
            padding-right: 5px;
        }

        .isi {
            margin: 12px 0;
            text-align: justify;
            font-size: 12pt;
        }

        .penutup {
            margin: 10px 0;
            text-align: justify;
            font-size: 12pt;
        }

        /* TTD */
        .ttd-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .ttd-qr {
            text-align: center;
            font-size: 9pt;
            color: #555;
        }

        .ttd-qr .qr-box {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            margin: 0 auto 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #aaa;
        }

        .ttd-right {
            text-align: center;
            font-size: 12pt;
        }

        .ttd-right .ttd-space {
            height: 80px;
        }

        .ttd-right .ttd-nama {
            font-weight: 900;
            text-decoration: underline;
            font-size: 13pt;
        }

        .ttd-right .ttd-nip {
            font-size: 10pt;
            margin-top: 2px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- KOP --}}
    <div class="kop">
        <img class="kop-logo" src="{{ 'file://' . str_replace('\\', '/', public_path('img/logo-kab.png')) }}" alt="Logo Kabupaten Blitar">
        <div class="kop-text">
            <div class="l1">PEMERINTAH KABUPATEN BLITAR</div>
            <div class="l2">KECAMATAN TALUN</div>
            <div class="l3">KELURAHAN TALUN</div>
            <div class="l4">Jalan Raya Talun Nomor 57 Kecamatan Talun Kode Pos 66183</div>
            <div class="l5">Telp: (0342) 692 809 Email: kelurahantalun@example.com</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <div class="judul-title">{{ strtoupper($surat->jenis_surat) }}</div>
        @php
            $prefix = strtoupper(preg_replace('/[^A-Z]/', '', strtoupper($surat->jenis_surat)));
            $nomorSurat = substr($prefix, 0, 3) . rand(100, 999) . substr($prefix, -1) . date('Y');
        @endphp
        <div class="judul-nomor">Nomor: {{ $nomorSurat }}</div>
    </div>

    {{-- PEMBUKA --}}
    <p class="pembuka">Yang bertanda tangan di bawah ini, Lurah Talun, Kecamatan Talun, Kabupaten Blitar, menerangkan bahwa:</p>

    {{-- DATA IDENTITAS --}}
    <table class="data-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><strong>{{ strtoupper($surat->ocr->nama ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $surat->ocr->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat, Tgl Lahir</td>
            <td>:</td>
            <td>{{ strtoupper($surat->ocr->tempat_lahir ?? '-') }}, {{ \Carbon\Carbon::parse($surat->ocr->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $surat->ocr->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $surat->ocr->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ strtoupper($surat->ocr->pekerjaan ?? '-') }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ strtoupper(($surat->ocr->alamat ?? '-') . ($surat->ocr->rt_rw ? ' RT/RW ' . $surat->ocr->rt_rw : '') . ($surat->ocr->kelurahan ? ' Desa ' . $surat->ocr->kelurahan : '')) }}</td>
        </tr>
    </table>

    {{-- ISI --}}
    <p class="isi">Berdasarkan data dan pengamatan Pemerintah Kelurahan Talun, yang bersangkutan benar berdomisili dan bertempat tinggal di wilayah Kelurahan Talun sampai dengan surat ini dibuat.</p>

    <p class="penutup">Surat keterangan ini dibuat untuk keperluan administrasi dan keperluan lain yang sah sesuai dengan peraturan yang berlaku.</p>

    {{-- TTD --}}
    <div class="ttd-section">
        <div class="ttd-qr">
            <div class="qr-box">[QR]</div>
            <span>Scan untuk validasi</span>
        </div>
        <div class="ttd-right">
            <p>Talun, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Kelurahan Talun</p>
            <div class="ttd-space">
                @if(isset($signature_data) && $signature_data)
                    <img src="{{ $signature_data }}" style="max-height:80px; max-width:180px;" alt="Tanda Tangan">
                @endif
            </div>
            <div class="ttd-nama">{{ $surat->petugas->nama ?? 'Nama Kepala Lurah' }}</div>
            <div class="ttd-nip">NIP. -</div>
        </div>
    </div>

</div>
</body>
</html>
