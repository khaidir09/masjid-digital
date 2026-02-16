<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kegiatan', 'tanggal_kegiatan', 'deskripsi', 'is_active'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi: Galeri punya banyak foto
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
