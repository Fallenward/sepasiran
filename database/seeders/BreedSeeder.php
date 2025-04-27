<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Breed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create('fa_IR'); // تولید داده‌های فارسی

        foreach (range(1, 15) as $i) {
            DB::table('breeds')->insert([
                'name'        => $faker->word, // کلمه تصادفی به عنوان نام نژاد
                'description' => Str::limit($faker->paragraph(3, true), 200, '…'), // توضیحات نژاد (حداکثر 200 کاراکتر)
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
