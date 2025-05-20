<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Clear table to avoid duplicates
        DB::table('insurances')->truncate();

        foreach (range(1, 10) as $i) {
            $name = $faker->unique()->word . ' ' . $faker->word;
            $baseSlug = Str::slug($name);
            $slug = $this->uniqueSlug($baseSlug);

            DB::table('insurances')->insert([
                'name'          => $name,
                'slug'          => $slug,
                'description'   => $faker->sentence,
                'logo'          => "insurance-$i.png",
                'contact_phone' => $faker->phoneNumber,
                'contact_email' => $faker->unique()->safeEmail,
                'website'       => $faker->url,
                'is_active'     => 1,
                'is_featured'   => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $count = 1;

        while (DB::table('insurances')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
