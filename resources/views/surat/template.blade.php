<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenis_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }

        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo-td {
            width: 100px;
            text-align: left;
        }

        .kop-logo {
            width: 85px;
            height: 85px;
        }

        .kop-text-td {
            text-align: center;
        }

        .kop-text .l1 { font-size: 12pt; font-weight: normal; }
        .kop-text .l2 { font-size: 12pt; font-weight: normal; }
        .kop-text .l3 { font-size: 18pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin: 2px 0; }
        .kop-text .l4 { font-size: 9pt; }
        .kop-text .l5 { font-size: 9pt; }

        /* JUDUL */
        .judul {
            text-align: center;
            margin: 15px 0;
        }

        .judul-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
        }

        .judul-nomor {
            font-size: 11pt;
        }

        /* BODY */
        .pembuka {
            margin: 15px 0 10px 0;
            text-align: justify;
        }

        .data-table {
            margin: 5px 0 15px 25px;
            font-size: 12pt;
            width: calc(100% - 25px);
            border-collapse: collapse;
        }

        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .data-table td:first-child {
            width: 160px;
        }

        .data-table td:nth-child(2) {
            width: 20px;
        }

        .isi {
            margin: 15px 0;
            text-align: justify;
        }

        .penutup {
            margin: 15px 0 25px 0;
            text-align: justify;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .ttd-table td {
            vertical-align: top;
            width: 50%;
        }

        .ttd-qr {
            text-align: center;
            font-size: 9pt;
            color: #555;
            padding-top: 40px; /* Align roughly with bottom of signature space */
        }

        .qr-box {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            margin: 0 auto 5px auto;
            line-height: 70px;
            text-align: center;
            color: #aaa;
            font-size: 8pt;
        }

        .ttd-right {
            text-align: center;
        }

        .ttd-right p {
            margin: 2px 0;
        }

        .ttd-space {
            height: 80px;
            margin: 10px 0;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
        }

        .ttd-nip {
            font-size: 10pt;
            margin-top: 3px;
        }
    </style>
</head>
<body>

    {{-- KOP --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo-td">
                <img class="kop-logo" src="{{ 'file://' . str_replace('\\', '/', public_path('img/logo-kab.png')) }}" alt="Logo">
            </td>
            <td class="kop-text-td">
                <div class="kop-text">
                    <div class="l1">PEMERINTAH KABUPATEN BLITAR</div>
                    <div class="l2">KECAMATAN TALUN</div>
                    <div class="l3">KELURAHAN TALUN</div>
                    <div class="l4">Jalan Raya Talun Nomor 57 Kecamatan Talun Kode Pos 66183</div>
                    <div class="l5">Telp: (0342) 692 809 Email: kelurahantalun@example.com</div>
                </div>
            </td>
        </tr>
    </table>

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
            <td>{{ strtoupper(($surat->ocr->alamat ?? '-') . ($surat->ocr->rt_rw ? ' RT/RW ' . $surat->ocr->rt_rw : '') . ($surat->ocr->kelurahan ? ' Desa /Desa: ' . $surat->ocr->kelurahan : '')) }}</td>
        </tr>
    </table>

    {{-- ISI --}}
    <p class="isi">Berdasarkan data dan pengamatan Pemerintah Kelurahan Talun, yang bersangkutan benar berdomisili dan bertempat tinggal di wilayah Kelurahan Talun sampai dengan surat ini dibuat.</p>

    <p class="penutup">Surat keterangan ini dibuat untuk keperluan administrasi dan keperluan lain yang sah sesuai dengan peraturan yang berlaku.</p>

    {{-- TTD --}}
    <table class="ttd-table">
        <tr>
            <td>
                <div class="ttd-qr">
                    <div class="qr-box">[QR]</div>
                    <div>Scan untuk validasi</div>
                </div>
            </td>
            <td>
                <div class="ttd-right">
                    <p>Talun, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Kepala Kelurahan Talun</p>
                    <div class="ttd-space">
                        @if(isset($signature_data) && $signature_data)
                            <img src="{{ $signature_data }}" style="height: 75px;" alt="Tanda Tangan">
                        @endif
                    </div>
                    <div class="ttd-nama">{{ $nama_petugas ?? ($surat->petugas->nama ?? 'Nama Kepala Lurah') }}</div>
                    <div class="ttd-nip">NIP. {{ str_replace('NIP. ', '', $nip_petugas ?? '-') }}</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
