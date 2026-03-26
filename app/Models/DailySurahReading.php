<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySurahReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'waktu_sholat',
        'surah',
    ];
}
