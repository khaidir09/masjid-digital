<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasJumat extends Model
{
    use HasFactory;

    protected $table = 'petugas_jumat';

    protected $fillable = [
        'tanggal', 'khatib', 'imam', 'muadzin', 'bilal', 'judul_ceramah'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
