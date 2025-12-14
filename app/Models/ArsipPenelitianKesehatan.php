<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipPenelitianKesehatan extends Model
{
    use HasFactory;

    protected $table = 'arsip_izin_kesehatan';
    protected $fillable = [
        'user_id',
        'jenis_izin_id', // Foreign Key
        'nomor_izin',
        'nomor_surat',
        'nama',
        'deskripsi',
        'tgl_surat',
        'tgl_terbit',    // Diisi Admin nanti
        'file',
        'status',
        'catatan_revisi',
        'file_revisi',
        'tempat_praktek',
        'file_surat_izin'
    ];

    protected $casts = [
        'file' => 'array',
        'tgl_surat' => 'date',
        'tgl_terbit' => 'date',
        'file_revisi' => 'array',
        'file_surat_izin' => 'array',
    ];


    public function jenisIzin(): BelongsTo
    {
        return $this->belongsTo(JenisIzin::class, 'jenis_izin_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
