<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisIzin extends Model
{
    use HasFactory;

    protected $table = 'jenis_izins';
    protected $fillable = [
        'id',
        'nama',
        'kategori'
    ];

    public function arsip(): HasMany
    {
        return $this->hasMany(ArsipPenelitianKesehatan::class, 'jenis_izin_id');
    }
}
