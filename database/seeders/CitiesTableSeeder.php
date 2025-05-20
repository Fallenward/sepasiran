<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Tehran',
            'Mashhad',
            'Isfahan',
            'Karaj',
            'Shiraz',
            'Tabriz',
            'Qom',
            'Ahvaz',
            'Kermanshah',
            'Urmia',
            'Rasht',
            'Zahedan',
            'Kerman',
            'Arak',
            'Hamedan',
            'Yazd',
            'Bandar Abbas',
            'Sanandaj',
            'Khorramabad',
            'Gorgan'
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
