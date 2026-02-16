<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';

    protected $fillable = [
        'tanggal', 'kategori', 'sumber_atau_tujuan',
        'nominal', 'keterangan', 'bukti_path', 'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Relasi: Siapa yang input data ini?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
