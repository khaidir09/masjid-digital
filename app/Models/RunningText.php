<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    use HasFactory;

    protected $fillable = ['teks', 'tipe', 'is_active', 'urutan', 'kecepatan'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
