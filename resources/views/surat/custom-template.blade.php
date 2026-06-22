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
    </style>
</head>
<body>
    @php
        $html = $surat->html_content;

        // Ganti URL logo agar terbaca oleh DomPDF
        $logoUrl = asset('img/logo-kab.png');
        $logoLocalPath = 'file://' . str_replace('\\', '/', public_path('img/logo-kab.png'));
        $html = str_replace($logoUrl, $logoLocalPath, $html);

        // Ganti TTD Space
        if (isset($signature_data) && $signature_data) {
            $html = str_replace(
                '<div class="ttd-space" id="preview-ttd-space" style="height: 80px; margin: 10px 0;"></div>',
                '<div class="ttd-space" id="preview-ttd-space" style="height: 80px; margin: 10px 0;"><img src="' . $signature_data . '" style="height: 75px;"></div>',
                $html
            );
        }

        // Ganti Nama & NIP
        if (isset($nama_petugas)) {
            $html = str_replace('id="preview-nama-petugas">Nama Kepala Lurah</p>', 'id="preview-nama-petugas">' . $nama_petugas . '</p>', $html);
        }
        if (isset($nip_petugas) && $nip_petugas !== '-') {
            $html = str_replace('id="preview-nip-petugas">NIP. -</p>', 'id="preview-nip-petugas">NIP. ' . $nip_petugas . '</p>', $html);
        }
    @endphp
    
    {!! $html !!}
</body>
</html>
