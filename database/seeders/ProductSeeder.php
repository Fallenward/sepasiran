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
        $userId = DB::table('users')->value('id') ?? 1;
        $categoryId = DB::table('categories')->value('id') ?? 1;

        DB::table('products')->delete();

        $items = [
            ['title' => 'غذای خشک سگ بالغ', 'price' => 890000, 'discount' => 790000, 'thumb' => 'images/demo/products/product-01.jpg', 'desc' => 'فرمول متعادل پروتئین برای سگ‌های بالغ.'],
            ['title' => 'اسباب‌بازی طنابی', 'price' => 185000, 'discount' => null, 'thumb' => 'images/demo/products/product-02.jpg', 'desc' => 'مقاوم برای جویدن و بازی تعاملی.'],
            ['title' => 'قلاده و بند چرمی', 'price' => 320000, 'discount' => 275000, 'thumb' => 'images/demo/products/product-03.jpg', 'desc' => 'طراحی راحت برای پیاده‌روی روزانه.'],
            ['title' => 'بستر نرم سگ', 'price' => 1250000, 'discount' => null, 'thumb' => 'images/demo/products/product-04.jpg', 'desc' => 'استراحت آرام با روکش قابل شستشو.'],
            ['title' => 'کوله حمل حیوان', 'price' => 980000, 'discount' => 850000, 'thumb' => 'images/demo/products/product-05.jpg', 'desc' => 'مناسب رفت‌وآمد شهری و سفر کوتاه.'],
            ['title' => 'شامپوی ملایم سگ', 'price' => 210000, 'discount' => null, 'thumb' => 'images/demo/products/product-06.jpg', 'desc' => 'مراقبت پوست و موی حساس.'],
            ['title' => 'ظرف آب و غذا دوقلو', 'price' => 165000, 'discount' => 145000, 'thumb' => 'images/demo/products/product-01.jpg', 'desc' => 'استیل ضدزنگ با پایه ضدلغزش.'],
            ['title' => 'توپ تمرینی هوشمند', 'price' => 240000, 'discount' => null, 'thumb' => 'images/demo/products/product-02.jpg', 'desc' => 'برای تخلیه انرژی و بازی روزانه.'],
        ];

        foreach ($items as $i => $item) {
            $slug = Str::slug($item['title']) ?: 'product-' . ($i + 1);
            Product::query()->updateOrCreate(
                ['slug' => $slug . '-' . ($i + 1)],
                [
                    'title' => $item['title'],
                    'description' => $item['desc'],
                    'price' => $item['price'],
                    'discount_price' => $item['discount'],
                    'stock' => rand(8, 40),
                    'is_active' => true,
                    'is_featured' => $i < 4,
                    'sku' => 'SP-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'user_id' => $userId,
                    'category_id' => $categoryId,
                    'thumbnail' => $item['thumb'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
        }
    }
}