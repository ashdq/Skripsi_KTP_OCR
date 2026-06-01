<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$warga = \App\Models\Warga::find(2); // ID 2 has Ocr 9
$ocrData = \App\Models\Ocr::where('warga_id', 2)->first();

$view = view('warga.pengurusan', [
    'warga' => $warga,
    'ocrData' => $ocrData,
    'existingKtp' => [
        'path' => 'dummy',
        'name' => 'ktp.png',
        'type' => 'image',
        'preview_url' => '...',
        'download_url' => '...',
    ],
    'existingKk' => [
        'path' => 'dummy',
        'name' => 'kk.jpg',
        'type' => 'image',
        'preview_url' => '...',
        'download_url' => '...',
    ],
])->render();

if (strpos($view, 'value="MAIDA PERMATA"') !== false) {
    echo "SUCCESS: MAIDA PERMATA found in HTML!\n";
} else {
    echo "ERROR: MAIDA PERMATA NOT found in HTML!\n";
}
