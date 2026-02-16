<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeColorSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'blue',
                'label' => 'Ocean Blue',
                'main_color' => '#3b82f6',
                'dark_color' => '#1e3a8a',
                'light_color' => '#bfdbfe',
            ],
            [
                'name' => 'violet',
                'label' => 'Royal Violet',
                'main_color' => '#8b5cf6',
                'dark_color' => '#4c1d95',
                'light_color' => '#ddd6fe',
            ],
            [
                'name' => 'rose',
                'label' => 'Soft Rose',
                'main_color' => '#f43f5e',
                'dark_color' => '#881337',
                'light_color' => '#fecdd3',
            ],
            [
                'name' => 'amber',
                'label' => 'Golden Amber',
                'main_color' => '#f59e0b',
                'dark_color' => '#78350f',
                'light_color' => '#fde68a',
            ],
            [
                'name' => 'emerald',
                'label' => 'Emerald Green',
                'main_color' => '#10b981',
                'dark_color' => '#064e3b',
                'light_color' => '#a7f3d0',
            ],
        ];

        foreach ($themes as $theme) {
            DB::table('theme_colors')->updateOrInsert(
                ['name' => $theme['name']],
                array_merge($theme, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
