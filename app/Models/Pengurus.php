<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus'; // Laravel kadang mengira jamaknya 'penguruses'

    protected $fillable = [
        'nama', 'jabatan', 'no_hp', 'foto_path', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: Satu pengurus mungkin punya satu akun user
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
