<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            'Football', 'Futsal', 'Basketball', 'Badminton',
            'Volleyball', 'Tennis', 'Golf', 'Baseball',
        ];

        foreach ($sports as $index => $name) {
            Sport::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'icon' => Str::lower($name),
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
