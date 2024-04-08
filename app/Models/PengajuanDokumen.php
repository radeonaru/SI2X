<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDokumen extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_dokumen';
    protected $primaryKey = 'id_pengajuandokumen';
    protected $fillable = [
        'no_rt',
        'id_dokumen',
        'nik_pengaju',
        'nama_pengaju',
        'status_pengajuan',
        'catatan',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }
}
