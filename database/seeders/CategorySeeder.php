<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');
        $rootIds = [];

        foreach (range(1, 5) as $i) {
            $name = $faker->unique()->word;
            $rootIds[] = DB::table('categories')->insertGetId([
                'parent_id' => 0,
                'name' => $name,
                'slug' => $this->uniqueSlug(Str::slug($name) ?: "cat-root-$i"),
                'description' => $faker->sentence,
                'image' => "cat-root-$i.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (range(1, 10) as $i) {
            $name = $faker->unique()->word;
            DB::table('categories')->insert([
                'parent_id' => $rootIds[array_rand($rootIds)],
                'name' => $name,
                'slug' => $this->uniqueSlug(Str::slug($name) ?: "cat-$i"),
                'description' => $faker->sentence,
                'image' => "cat-$i.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'category';
        $count = 1;

        while (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}