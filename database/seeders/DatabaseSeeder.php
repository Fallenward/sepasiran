<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');
        foreach (range(1, 20) as $i) {
            DB::table('blogs')->insert([
                'title'       => $title = $faker->sentence(3),
                'slug'        => Str::slug($title)."-$i",
                'content'     => $faker->paragraphs(5, true),
                'description' => $faker->paragraph,
                'image'       => "blog-$i.jpg",
                'category_id' => rand(1, 10),
                'author_id'   => rand(1, 5),
                'rating'      => rand(1, 5),
                'views'       => rand(100, 5000),
                'likes'       => rand(0, 500),
                'status'      => $faker->randomElement(['draft', 'published']),
                'comments_enabled' => $faker->boolean,
                'created_at'  => Carbon::now()->subDays(rand(1, 60)),
                'updated_at'  => Carbon::now(),
            ]);
        }
    }
}

class BlogRatingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $i) {
            DB::table('blog_ratings')->insert([
                'blog_id'    => rand(1, 20),
                'rating'     => rand(1, 5),
                'ip'         => $faker->ipv4(),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker       = Faker::create();
        // مقادیر مجاز دقیقاً مطابق Enum
        $types       = ['TRAINER', 'CLINIC', 'BREEDER'];
        $statuses    = ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'];

        foreach (range(1, 50) as $i) {
            DB::table('bookings')->insert([
                'user_id'          => rand(1, 20),
                'service_type'     => $types[array_rand($types)],      // ✔ مجاز
                'provider_id'      => rand(1, 10),
                'city_id'          => rand(1, 30),
                'appointment_date' => Carbon::now()->addDays(rand(1, 30)),
                'status'           => $statuses[array_rand($statuses)],// ✔ مجاز
                'notes'            => $faker->sentence,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}


class CartSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['active', 'expired', 'purchased'];

        foreach (range(1, 30) as $i) {
            DB::table('carts')->insert([
                'user_id'     => rand(1, 20),
                'course_id'   => rand(1, 10),
                'quantity'    => rand(1, 3),
                'price'       => rand(50, 100) * 1000,
                'discount_id' => rand(1, 5),
                'status'      => $statuses[array_rand($statuses)],
                'created_at'  => Carbon::now()->subDays(rand(1, 10)),
                'updated_at'  => Carbon::now(),
            ]);
        }
    }
}
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker      = Faker::create('fa_IR');
        $usedSlugs  = [];          // برای کنترل یکتا بودن
        $rootIds    = [];

        /* ---------- ۱. ایجاد ۵ دستهٔ ریشه ---------- */
        foreach (range(1, 5) as $i) {
            $name = $faker->unique()->word;         // نام یکتا
            $slug = Str::slug($name);

            // تضمین ۱۰۰٪ یکتا بودن slug
            $slug = $this->uniqueSlug($slug, $usedSlugs);

            $rootIds[] = DB::table('categories')->insertGetId([
                'parent_id'   => 0,
                'name'        => $name,
                'slug'        => $slug,
                'description' => $faker->sentence,
                'image'       => "cat-root-$i.jpg",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        /* ---------- ۲. ایجاد ۱۰ زیردسته ---------- */
        foreach (range(1, 10) as $i) {
            $name = $faker->unique()->word;
            $slug = $this->uniqueSlug(Str::slug($name), $usedSlugs);

            DB::table('categories')->insert([
                'parent_id'   => $rootIds[array_rand($rootIds)],
                'name'        => $name,
                'slug'        => $slug,
                'description' => $faker->sentence,
                'image'       => "cat-$i.jpg",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    /**
     * اگر slug تکراری باشد با پسوند -1، -2 … یکتا می‌شود.
     */
    private function uniqueSlug(string $base, array &$taken): string
    {
        $slug   = $base;
        $count  = 1;
        while (in_array($slug, $taken)) {
            $slug = "{$base}-{$count}";
            $count++;
        }
        $taken[] = $slug;
        return $slug;
    }
}


class DogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');
        foreach (range(1, 30) as $i) {
            DB::table('dogs')->insert([
                'title'       => $faker->firstName,
                'color'       => $faker->safeColorName,
                'age'         => rand(1, 10),
                'birth_date'  => Carbon::now()->subYears(rand(1,10)),
                'cover_type'  => $faker->randomElement(['short','long']),
                'eye_color'   => $faker->safeColorName,
                'shajare'     => $faker->boolean,
                'breed_id'    => rand(1,15),
                'father_name' => $faker->firstNameMale,
                'father_breed_id'=> rand(1,15),
                'mother_name' => $faker->firstNameFemale,
                'mother_breed_id'=> rand(1,15),
                // keeping ancestor fields nullable for brevity
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);
        }
    }
}

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');
        $usedSlugs = [];

        foreach (range(1, 6) as $i) {
            $baseName = 'بیمه '.$faker->unique()->word;
            $slug     = Str::slug($baseName);

            // تضمین یکتایی اسلاگ
            $suffix = 1;
            while (in_array($slug, $usedSlugs)) {
                $slug = Str::slug($baseName)."-$suffix";
                $suffix++;
            }
            $usedSlugs[] = $slug;

            DB::table('insurances')->insert([
                'name'          => $baseName,
                'slug'          => $slug,
                'description'   => $faker->paragraph,
                'logo'          => "insurance-$i.png",
                'contact_phone' => $faker->phoneNumber,
                'contact_email' => $faker->unique()->safeEmail,
                'website'       => $faker->url,
                'is_active'     => true,
                'is_featured'   => (bool)rand(0, 1),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');
        foreach (range(1, 20) as $i) {
            $title = $faker->word;
            DB::table('products')->insert([
                'title'       => $title,
                'description' => $faker->paragraph,
                'slug'        => Str::slug($title)."-$i",
                'price'       => rand(100, 1000)*1000,
                'discount_price'=> rand(0,1)? rand(50, 99)*1000: null,
                'stock'       => rand(0, 200),
                'is_active'   => true,
                'is_featured' => rand(0,1),
                'sku'         => Str::upper(Str::random(8)),
                'barcode'     => rand(100000000000,999999999999),
                'category_id' => rand(1,10),
                'brand_id'    => rand(1,5),
                'thumbnail'   => "prod-$i.jpg",
                'images'      => json_encode(["prod-$i-1.jpg","prod-$i-2.jpg"]),
                'buy_link'    => $faker->url,
                'created_by'  => 1,
                'updated_by'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);
        }
    }
}

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fa_IR');

        foreach (range(1, 10) as $i) {
            $title = $faker->word;

            DB::table('services')->insert([
                'title'       => $title,
                'slug'        => Str::slug($title),
                // حداکثر 200 نویسه
                'description' => Str::limit($faker->paragraph(3, true), 200, '…'),
                'image'       => "service-$i.jpg",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['گوش دادن','دستورات پایه','جستجو','حفاظت'] as $skill) {
            DB::table('skills')->insert([
                'title'      => $skill,
                'description'=> 'توضیح '.$skill,
                'is_active'  => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // مقادیر مجاز دقیقاً مطابق Enum جدول
        $statuses = ['pending', 'completed', 'failed', 'refunded'];

        foreach (range(1, 50) as $i) {
            DB::table('transactions')->insert([
                'user_id'        => rand(1, 20),
                'transaction_id' => Str::uuid(),
                'cart_ids'       => json_encode([rand(1, 30), rand(1, 30)]),
                'description'    => 'پرداخت سفارش',
                'card_number'    => '610433******' . rand(1000, 9999),
                'ref_id'         => 'RF' . time() . rand(100, 999),
                'status'         => $statuses[array_rand($statuses)],   // ✔ مجاز
                'amount'         => rand(100, 1000) * 1000,
                'cart_price'     => rand(100, 1000) * 1000,
                'tax'            => 0,
                'discount'       => 0,
                'payment_method' => 'online',   // اگر این ستون Enum است، گزینهٔ صحیح را بگذارید
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);
        }
    }
}

// Finally register all in DatabaseSeeder
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            BlogSeeder::class,
            BlogRatingSeeder::class,
            BookingSeeder::class,
            BreedSeeder::class,
            CartSeeder::class,
            CategorySeeder::class,
            DogSeeder::class,
            InsuranceSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            SkillSeeder::class,
            TransactionSeeder::class,
            CitySeeder::class,
            BreedSeeder::class,
            SpecialtySeeder::class,
            CoachSeeder::class,
        ]);
    }
}
