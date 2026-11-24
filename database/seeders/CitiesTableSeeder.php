<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'تهران',
            'مشهد',
            'اصفهان',
            'کرج',
            'شیراز',
            'تبریز',
            'قم',
            'اهواز',
            'کرمانشاه',
            'ارومیه',
            'رشت',
            'زاهدان',
            'کرمان',
            'اراک',
            'همدان',
            'یزد',
            'بندرعباس',
            'سنندج',
            'خرم‌آباد',
            'گرگان',
        ];

        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                ['name' => $city],
                [
                    'user_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}