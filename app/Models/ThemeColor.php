<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ThemeColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'label', 'main_color', 'dark_color', 'light_color', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
