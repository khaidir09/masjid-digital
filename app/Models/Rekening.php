<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekening extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bank', 'nama_akun', 'nomor_rekening', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
