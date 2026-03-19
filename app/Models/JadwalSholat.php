<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSholat extends Model
{
    use HasFactory;

    protected $table = 'jadwal_sholat';

    protected $fillable = [
        'tanggal',
        'tanggal_hijriah',
        'imsak',
        'subuh',
        'terbit',
        'dhuha',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
