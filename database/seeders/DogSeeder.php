<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DogSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $breedIds = DB::table('breeds')->pluck('id');
        if (!$userId || $breedIds->isEmpty()) {
            return;
        }

        $faker = Faker::create('fa_IR');
        foreach (range(1, 30) as $i) {
            DB::table('dogs')->insert([
                'user_id' => $userId,
                'title' => $faker->firstName,
                'color' => $faker->safeColorName,
                'age' => rand(1, 10),
                'birth_date' => Carbon::now()->subYears(rand(1, 10)),
                'cover_type' => $faker->randomElement(['short', 'long']),
                'eye_color' => $faker->safeColorName,
                'shajare' => $faker->boolean,
                'breed_id' => $breedIds->random(),
                'father_name' => $faker->firstNameMale,
                'father_breed_id' => $breedIds->random(),
                'mother_name' => $faker->firstNameFemale,
                'mother_breed_id' => $breedIds->random(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}