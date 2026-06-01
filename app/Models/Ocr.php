<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocr extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'nomor_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'gol_darah',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'dokumen_id',
        'warga_id',
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}
