<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipPenelitianKesehatan extends Model
{
    use HasFactory;
    protected $table = 'arsip_penelitian_kesehatan';
    protected $fillable = [
        'id',
        'user_id',
        'nama',
        'deskripsi',
        'tgl_upload',
        'file',
        'status',
        'catatan_revisi',
    ];

    protected $casts = [
        'file' => 'array',
        'tgl_upload' => 'date',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
