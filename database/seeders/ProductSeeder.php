<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Optional: Clear the products table first to avoid duplicates
        DB::table('products')->truncate();

        $faker = \Faker\Factory::create();

        // Create 10 products with unique slugs using factory if you have it
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->unique()->word;

            $slug = $this->uniqueSlug(Str::slug($title));

            Product::factory()->create([
                'title' => $title,
                'slug' => $slug,
                'user_id' => 1,
                'category_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                // You can add more fields here if needed
            ]);
        }

        // Create a manual sample product with unique slug
        $sampleTitle = 'Sample Product';
        $sampleSlug = $this->uniqueSlug(Str::slug($sampleTitle));

        Product::create([
            'title' => $sampleTitle,
            'description' => 'Description here',
            'slug' => $sampleSlug,
            'price' => 100,
            'discount_price' => 90,
            'user_id' => 1,
            'stock' => 20,
            'is_active' => true,
            'category_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            // add other fields as needed
        ]);
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $count = 1;

        while (DB::table('products')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
