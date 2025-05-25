<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\AdminUserSeeder;

use Database\Seeders\SlidersTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([SlidersTableSeeder::class]);
        $faker = Faker::create('fa_IR');
        foreach (range(1, 20) as $i) {
            DB::table('blogs')->insert([
                'title'       => $title = $faker->sentence(3),
                'slug'        => Str::slug($title)."-$i",
                'content'     => $faker->paragraphs(1, true),
                'description' => $faker->paragraph,
                'image'       => "images/blog.svg",
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
                [
                    'user_id' => 3,
                    'service_type' => 'TRAINER',
                    'provider_id' => 5,
                    'city_id' => 20,
                    'appointment_date' => '2025-05-27 08:48:45',
                    'booking_date' => now(), // ✅ Add this line
                    'status' => 'PENDING',
                    'notes' => 'Some notes here...',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]

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
        $faker   = Faker::create('fa_IR');
        $rootIds = [];

        // Optional: Clear existing categories before seeding
        // DB::table('categories')->truncate();

        /* ---------- ۱. ایجاد ۵ دستهٔ ریشه ---------- */
        foreach (range(1, 5) as $i) {
            $name = $faker->unique()->word;
            $slug = $this->uniqueSlug(Str::slug($name));

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
            $slug = $this->uniqueSlug(Str::slug($name));

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
     * Ensures the slug is unique in the database by appending a number if needed.
     */
    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $count = 1;

        while (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

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
                'user_id'       => 1,
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

        foreach (range(1, 6) as $i) {
            // Static titles array
            $staticTitles = [
                'انتخاب مربی',
                'تهیه سگ',
                'دوره های آنلاین',
                'فروشگاه ها',
                'کلینیک ها و بیمه حیوانات',
                'پرورش دهنده',
            ];

            // Select title based on index (cycle through titles if $i exceeds array length)
            $title = $staticTitles[($i - 1) % count($staticTitles)];

            DB::table('services')->insert([
                'title' => $title,
                'slug' => Str::slug($title, '-'), // Generate slug from title (Persian slugs are supported)
                'description' => Str::limit(fake()->paragraph(3, true), 200, '…'), // Use fake() for description
                'image' => "images/service-$i.png",
                'created_at' => now(),
                'updated_at' => now(),
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
    public function run()
    {
        // Get an existing user (e.g., first coach)
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', 'coach');
        })->first();

        // Fallback if no coach user exists
        if (! $user) {
            $this->command->warn('No coach user found to attach transaction to.');
            return;
        }

        Transaction::create([
            'user_id' => $user->id,
            'transaction_id' => Str::uuid(),
            'cart_ids' => json_encode([1, 2]),
            'description' => 'پرداخت تستی',
            'card_number' => '610433******1234',
            'ref_id' => 'RF' . rand(1000000000000, 9999999999999),
            'status' => 'completed',
            'amount' => 150000,
            'cart_price' => 150000,
            'tax' => 0,
            'discount' => 0,
            'payment_method' => 'online',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
            BreedSeeder::class,
            CitiesTableSeeder::class,
            SpecialtySeeder::class,
            BlogSeeder::class,
            ReviewSeeder::class,
            CourseSeeder::class
        ]);
    }



}
