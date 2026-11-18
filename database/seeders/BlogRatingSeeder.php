<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogRatingSeeder extends Seeder
{
    public function run(): void
    {
        $blogIds = DB::table('blogs')->pluck('id');
        if ($blogIds->isEmpty()) {
            return;
        }

        $faker = Faker::create();
        foreach (range(1, 100) as $i) {
            DB::table('blog_ratings')->insert([
                'blog_id' => $blogIds->random(),
                'rating' => rand(1, 5),
                'ip' => $faker->ipv4(),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}