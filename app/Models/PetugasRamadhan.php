<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasRamadhan extends Model
{
    use HasFactory;

    protected $table = 'petugas_ramadhan';

    protected $fillable = [
        'tanggal', 'malam_ke', 'penceramah', 'imam', 'muadzin', 'bilal', 'judul_ceramah'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
