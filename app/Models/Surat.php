<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat',
        'tanggal_pengajuan',
        'status',
        'file_surat',
        'warga_id',
        'petugas_id',
        'ocr_id',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function ocr()
    {
        return $this->belongsTo(Ocr::class);
    }
}
