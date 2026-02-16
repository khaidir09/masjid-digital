<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasIed extends Model
{
    use HasFactory;

    protected $table = 'petugas_ied';

    protected $fillable = [
        'tanggal', 'khatib', 'imam', 'muadzin', 'bilal', 'judul_ceramah','ied'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
