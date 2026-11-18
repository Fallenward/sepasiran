<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fa_IR');
        $userId = DB::table('users')->value('id') ?? 1;
        $categoryId = DB::table('categories')->value('id') ?? 1;

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->unique()->word;
            $slug = $this->uniqueSlug(Str::slug($title) ?: "product-$i");

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => $faker->paragraph,
                    'price' => rand(100, 1000) * 1000,
                    'discount_price' => rand(0, 1) ? rand(50, 99) * 1000 : null,
                    'stock' => rand(0, 200),
                    'is_active' => true,
                    'is_featured' => (bool) rand(0, 1),
                    'sku' => Str::upper(Str::random(8)),
                    'user_id' => $userId,
                    'category_id' => $categoryId,
                    'thumbnail' => "prod-$i.jpg",
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'product';
        $count = 1;

        while (DB::table('products')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
