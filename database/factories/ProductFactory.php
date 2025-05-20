<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $title = $this->faker->words(2, true); // e.g. "Awesome Product"
        return [
            'title' => $title,
            'description' => $this->faker->paragraph(),
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discount_price' => null,
            'user_id' => 1, // You can replace with factory call or faker user id
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => false,
            'sku' => $this->faker->bothify('SKU-####'),
            'barcode' => $this->faker->ean13(),
            'category_id' => null, // Or random existing category id
            'brand_id' => null, // If you have brands, else remove this field
            'thumbnail' => null,
            'images' => json_encode([]),
            'buy_link' => null,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
