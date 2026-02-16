<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajianRutin extends Model
{
    use HasFactory;

    protected $table = 'pengajian_rutin';

    protected $fillable = [
        'tanggal', 'penceramah', 'judul_ceramah'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
