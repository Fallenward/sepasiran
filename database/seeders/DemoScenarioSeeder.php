<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Course;
use App\Models\Dog;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Rich, idempotent demo dataset for manual QA of dashboards and routes.
 *
 * Password for every demo account: password123
 *
 * Accounts:
 *  admin@sepasiran.test
 *  customer@sepasiran.test
 *  coach@sepasiran.test
 *  clinic@sepasiran.test
 *  breeder@sepasiran.test
 */
class DemoScenarioSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        $roleIds = $this->ensureRoles(['admin', 'user', 'coach', 'clinic', 'breeder']);
        $cityId = DB::table('cities')->value('id');
        $breedIds = DB::table('breeds')->orderBy('id')->pluck('id')->all();
        $categoryId = DB::table('categories')->value('id');
        $levelId = $this->ensureLevel();

        $admin = $this->user([
            'email' => 'admin@sepasiran.test',
            'phone' => '09120000001',
            'name' => 'مدیر',
            'family' => 'سیستم',
            'is_admin' => true,
            'wallet' => 0,
            'city_id' => $cityId,
            'bio' => 'حساب مدیریت سپاسیران برای تست پنل ادمین.',
            'profile_picture' => '/images/demo/dogs/dog-01.jpg',
        ], [$roleIds['admin'], $roleIds['user']]);

        $customer = $this->user([
            'email' => 'customer@sepasiran.test',
            'phone' => '09121110001',
            'name' => 'سارا',
            'family' => 'مشتری',
            'wallet' => 1250000,
            'city_id' => $cityId,
            'address' => 'تهران، خیابان ولیعصر',
            'bio' => 'صاحب سگ و کاربر رزرو خدمات.',
            'profile_picture' => '/images/demo/dogs/dog-02.jpg',
        ], [$roleIds['user']]);

        $coach = $this->user([
            'email' => 'coach@sepasiran.test',
            'phone' => '09122220001',
            'name' => 'رضا',
            'family' => 'مربی',
            'wallet' => 4800000,
            'city_id' => $cityId,
            'bio' => 'مربی آموزش اطاعت و رفتار.',
            'profile_picture' => '/images/demo/hero/hero-01.jpg',
        ], [$roleIds['coach'], $roleIds['user']]);

        $clinic = $this->user([
            'email' => 'clinic@sepasiran.test',
            'phone' => '09123330001',
            'name' => 'کلینیک',
            'family' => 'پارس',
            'wallet' => 9200000,
            'city_id' => $cityId,
            'address' => 'تهران، سعادت‌آباد',
            'bio' => 'کلینیک دامپزشکی برای نوبت و ویزیت.',
            'profile_picture' => '/images/demo/hero/hero-03.jpg',
        ], [$roleIds['clinic'], $roleIds['user']]);

        $breeder = $this->user([
            'email' => 'breeder@sepasiran.test',
            'phone' => '09124440001',
            'name' => 'کاوه',
            'family' => 'پرورش‌دهنده',
            'wallet' => 3500000,
            'city_id' => $cityId,
            'bio' => 'پرورش‌دهنده سگ‌های خانگی با شجره.',
            'profile_picture' => '/images/demo/dogs/dog-05.jpg',
        ], [$roleIds['breeder'], $roleIds['user']]);

        // Extra providers for listings / matching.
        $coach2 = $this->user([
            'email' => 'coach2@sepasiran.test',
            'phone' => '09122220002',
            'name' => 'مینا',
            'family' => 'مربی‌یار',
            'wallet' => 1500000,
            'city_id' => $cityId,
        ], [$roleIds['coach'], $roleIds['user']]);

        $clinic2 = $this->user([
            'email' => 'clinic2@sepasiran.test',
            'phone' => '09123330002',
            'name' => 'کلینیک',
            'family' => 'نیکان',
            'wallet' => 2100000,
            'city_id' => $cityId,
        ], [$roleIds['clinic'], $roleIds['user']]);

        $customerDogs = $this->seedOwnedDogs($customer, $breedIds, $cityId, [
            ['title' => 'بادی', 'status' => 'ready', 'image' => 'images/demo/dogs/dog-03.jpg'],
            ['title' => 'نلا', 'status' => 'ready', 'image' => 'images/demo/dogs/dog-04.jpg'],
        ]);

        $breederDogs = $this->seedOwnedDogs($breeder, $breedIds, $cityId, [
            ['title' => 'آرتور', 'status' => 'ready', 'image' => 'images/demo/dogs/dog-01.jpg'],
            ['title' => 'لوسی', 'status' => 'pending', 'image' => 'images/demo/dogs/dog-06.jpg'],
            ['title' => 'تیتو', 'status' => 'waiting', 'image' => 'images/demo/dogs/dog-07.jpg'],
            ['title' => 'رِکس', 'status' => 'sold', 'image' => 'images/demo/dogs/dog-08.jpg'],
            ['title' => 'مِلی', 'status' => 'ready', 'image' => 'images/demo/dogs/dog-02.jpg'],
        ]);

        $this->seedCourses($coach, $categoryId, $levelId);
        $this->seedCourses($coach2, $categoryId, $levelId, prefix: 'Advanced');

        $this->seedBookings([
            // customer <-> coach
            ['user' => $customer, 'provider' => $coach, 'dog' => $customerDogs[0] ?? null, 'type' => 'COACH', 'status' => 'CONFIRMED', 'days' => 0, 'price' => 850000],
            ['user' => $customer, 'provider' => $coach, 'dog' => $customerDogs[0] ?? null, 'type' => 'COACH', 'status' => 'PENDING', 'days' => 3, 'price' => 650000],
            ['user' => $customer, 'provider' => $coach2, 'dog' => $customerDogs[1] ?? null, 'type' => 'COACH', 'status' => 'COMPLETED', 'days' => -12, 'price' => 720000],
            // customer <-> clinic
            ['user' => $customer, 'provider' => $clinic, 'dog' => $customerDogs[1] ?? null, 'type' => 'CLINIC', 'status' => 'CONFIRMED', 'days' => 1, 'price' => 450000],
            ['user' => $customer, 'provider' => $clinic, 'dog' => $customerDogs[0] ?? null, 'type' => 'CLINIC', 'status' => 'OPEN', 'days' => 5, 'price' => 380000],
            ['user' => $customer, 'provider' => $clinic2, 'dog' => $customerDogs[1] ?? null, 'type' => 'CLINIC', 'status' => 'COMPLETED', 'days' => -20, 'price' => 510000],
            // customer <-> breeder
            ['user' => $customer, 'provider' => $breeder, 'dog' => $breederDogs[0] ?? null, 'type' => 'BREEDER', 'status' => 'PENDING', 'days' => 4, 'price' => 15000000],
            ['user' => $customer, 'provider' => $breeder, 'dog' => $breederDogs[1] ?? null, 'type' => 'BREEDER', 'status' => 'CONFIRMED', 'days' => 7, 'price' => 18000000],
        ], $cityId, $breedIds);

        foreach ([$customer, $coach, $clinic, $breeder, $admin] as $account) {
            $this->seedTransactions($account);
            $this->seedNotifications($account);
        }

        $this->command?->info('DemoScenarioSeeder complete.');
        $this->command?->table(
            ['Role', 'Email', 'Phone', 'Password'],
            [
                ['admin', 'admin@sepasiran.test', '09120000001', self::PASSWORD],
                ['customer', 'customer@sepasiran.test', '09121110001', self::PASSWORD],
                ['coach', 'coach@sepasiran.test', '09122220001', self::PASSWORD],
                ['clinic', 'clinic@sepasiran.test', '09123330001', self::PASSWORD],
                ['breeder', 'breeder@sepasiran.test', '09124440001', self::PASSWORD],
            ]
        );
    }

    private function ensureRoles(array $names): array
    {
        $ids = [];
        foreach ($names as $name) {
            $ids[$name] = Role::query()->firstOrCreate(['name' => $name])->id;
        }

        return $ids;
    }

    private function ensureLevel(): ?int
    {
        if (! DB::table('levels')->exists()) {
            return DB::table('levels')->insertGetId([
                'name' => 'مقدماتی',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('levels')->value('id');
    }

    private function user(array $attrs, array $roleIds): User
    {
        $user = User::query()->updateOrCreate(
            ['email' => $attrs['email']],
            array_merge($attrs, [
                'password' => Hash::make(self::PASSWORD),
                'status' => 'active',
                'is_verified' => true,
                'email_verified_at' => now(),
                'gender' => $attrs['gender'] ?? 'male',
            ])
        );

        $user->roles()->sync(array_values(array_unique($roleIds)));

        return $user->fresh();
    }

    private function seedOwnedDogs(User $owner, array $breedIds, $cityId, array $rows): array
    {
        $dogs = [];
        foreach ($rows as $i => $row) {
            $breedId = $breedIds[$i % max(count($breedIds), 1)] ?? null;
            $dog = Dog::query()->firstOrNew([
                'user_id' => $owner->id,
                'title' => $row['title'],
            ]);
            $payload = [
                'image' => $row['image'],
                'color' => ['مشکی', 'سفید', 'قهوه‌ای', 'طلایی'][$i % 4],
                'status' => $row['status'],
                'age' => 6 + $i,
                'birth_date' => now()->subMonths(6 + $i)->toDateString(),
                'cover_type' => 'short',
                'eye_color' => 'قهوه‌ای',
                'shajare' => $i % 2 === 0,
                'breed_id' => $breedId,
                'father_name' => 'پدر '.$row['title'],
                'mother_name' => 'مادر '.$row['title'],
                'father_breed_id' => $breedId,
                'mother_breed_id' => $breedId,
            ];
            if (\Schema::hasColumn('dogs', 'city_id')) {
                $payload['city_id'] = $cityId;
            }
            if (\Schema::hasColumn('dogs', 'breed')) {
                $payload['breed'] = 'demo';
            }
            $dog->forceFill($payload)->save();
            $dogs[] = $dog;
        }

        return $dogs;
    }

    private function seedCourses(User $coach, $categoryId, $levelId, string $prefix = 'Coach'): void
    {
        foreach ([1, 2, 3] as $i) {
            $title = "{$prefix} Course {$coach->id}-{$i}";
            Course::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'short_description' => 'دوره آموزشی دمو برای داشبورد مربی',
                    'description' => 'محتوای کامل دوره دمو برای تست مسیر مربی و لیست دوره‌ها.',
                    'is_active' => 1,
                    'thumbnail' => 'images/demo/products/product-0'.$i.'.jpg',
                    'user_id' => $coach->id,
                    'category_id' => $categoryId,
                    'level_id' => $levelId,
                    'price' => 900000 + ($i * 100000),
                    'discount_price' => 750000 + ($i * 50000),
                    'status' => 'published',
                    'is_featured' => $i === 1,
                    'has_certificate' => true,
                    'duration' => 45 * $i,
                    'view_count' => 20 * $i,
                ]
            );
        }
    }

    private function seedBookings(array $rows, $cityId, array $breedIds): void
    {
        foreach ($rows as $i => $row) {
            /** @var User $customer */
            $customer = $row['user'];
            /** @var User $provider */
            $provider = $row['provider'];
            $dog = $row['dog'];
            $when = now()->addDays((int) $row['days'])->setTime(10 + ($i % 6), 0);

            Booking::query()->updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_type' => $row['type'],
                    'status' => $row['status'],
                    'notes' => 'demo-'.$row['type'].'-'.$i,
                ],
                [
                    'dog_id' => $dog?->id,
                    'fulfillment_mode' => $i % 2 === 0 ? 'direct' : 'open',
                    'city_id' => $cityId,
                    'breed_id' => $dog?->breed_id ?? ($breedIds[0] ?? null),
                    'training_type' => $row['type'] === 'COACH' ? 'obedience' : null,
                    'appointment_date' => $when,
                    'preferred_date' => $when->toDateString(),
                    'preferred_slot' => $when->format('H:i'),
                    'slot' => $when->format('H:i'),
                    'booking_date' => now()->toDateString(),
                    'price' => $row['price'],
                    'tax' => 0,
                    'discount' => 0,
                    'total' => $row['price'],
                    'payment_method' => 'online',
                    'payment_status' => in_array($row['status'], ['COMPLETED', 'CONFIRMED'], true) ? 'paid' : 'pending',
                    'contact_name' => trim($customer->name.' '.$customer->family),
                    'contact_phone' => $customer->phone,
                ]
            );
        }
    }

    private function seedTransactions(User $user): void
    {
        // Wipe prior demo rows for this user so reseed stays predictable.
        Transaction::query()
            ->where('user_id', $user->id)
            ->where('description', 'like', 'demo-%')
            ->delete();

        $patterns = [
            [2, 450000, 'demo-wallet-topup'],
            [1, -180000, 'demo-booking-pay'],
            [0, 220000, 'demo-refund'],
            [-1, -95000, 'demo-store-buy'],
            [-2, 600000, 'demo-payout'],
            [-3, -120000, 'demo-service-fee'],
        ];

        foreach ($patterns as [$monthsAgo, $amount, $desc]) {
            Transaction::query()->create([
                'user_id' => $user->id,
                'transaction_id' => (string) Str::uuid(),
                'description' => $desc,
                'card_number' => '6037********1234',
                'ref_id' => 'RF'.Str::upper(Str::random(8)),
                'status' => 'completed',
                'amount' => $amount,
                'tax' => 0,
                'discount' => 0,
                'cart_price' => abs($amount),
                'payment_method' => 'online',
                'created_at' => now()->startOfMonth()->subMonths(abs($monthsAgo))->addDays(3),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedNotifications(User $user): void
    {
        Notification::query()
            ->where('user_id', $user->id)
            ->where('message', 'like', 'demo:%')
            ->delete();

        $items = [
            ['title' => 'خوش آمدید', 'message' => 'demo: حساب دمو آماده تست داشبورد است.', 'read' => false],
            ['title' => 'رزرو جدید', 'message' => 'demo: یک رزرو جدید برای پیگیری ثبت شد.', 'read' => false],
            ['title' => 'کیف پول', 'message' => 'demo: تراکنش نمونه به کیف پول اضافه شد.', 'read' => true],
            ['title' => 'یادآوری', 'message' => 'demo: پروفایل و تصویر حساب را بررسی کنید.', 'read' => true],
        ];

        foreach ($items as $i => $item) {
            Notification::query()->create([
                'user_id' => $user->id,
                'title' => $item['title'],
                'message' => $item['message'],
                'read_at' => $item['read'] ? now()->subHours($i + 1) : null,
                'created_at' => now()->subHours($i * 3),
                'updated_at' => now(),
            ]);
        }
    }
}