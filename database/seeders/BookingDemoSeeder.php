<?php

namespace Database\Seeders;

use App\Models\Dog;
use App\Models\OpenSession;
use App\Models\ProviderAvailabilityRule;
use App\Models\ProviderAvailabilitySetting;
use App\Models\ProviderService;
use App\Models\ProviderServicePrice;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Booking QA dataset on top of DemoScenarioSeeder.
 * Password for all demo accounts: password123
 */
class BookingDemoSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        $this->call(DemoScenarioSeeder::class);

        $customer = User::query()->where('email', 'customer@sepasiran.test')->firstOrFail();
        $coach = User::query()->where('email', 'coach@sepasiran.test')->firstOrFail();
        $clinic = User::query()->where('email', 'clinic@sepasiran.test')->firstOrFail();
        $breeder = User::query()->where('email', 'breeder@sepasiran.test')->firstOrFail();

        $customer->forceFill(['wallet' => 10_000_000])->save();
        $coach->forceFill(['wallet' => 500_000])->save();

        Dog::query()->where('user_id', $customer->id)->update(['status' => 'owned']);

        $specialtyId = Specialty::query()->value('id');

        $this->seedCoachServices($coach, $specialtyId);
        $this->seedClinicServices($clinic, $specialtyId);
        $this->seedBreederServices($breeder, $specialtyId);

        foreach ([$coach, $clinic, $breeder] as $provider) {
            $this->seedAvailability($provider);
            $this->seedLegacyPriceFallback($provider);
        }

        $this->seedOpenSession($coach);

        $this->command?->info('BookingDemoSeeder complete.');
        $this->command?->table(
            ['Who', 'Login', 'Password', 'Start here'],
            [
                ['Customer', 'customer@sepasiran.test or 09121110001', self::PASSWORD, '/booking?type=coach&provider='.$coach->id],
                ['Coach', 'coach@sepasiran.test or 09122220001', self::PASSWORD, '/account/bookings?tab=inbox'],
                ['Clinic', 'clinic@sepasiran.test or 09123330001', self::PASSWORD, '/account/services'],
                ['Breeder', 'breeder@sepasiran.test or 09124440001', self::PASSWORD, '/account/availability'],
            ]
        );
        $this->command?->info('Locked coach book URL: /booking?type=coach&provider='.$coach->id);
    }

    private function seedCoachServices(User $coach, $specialtyId): void
    {
        if (! Schema::hasTable('provider_services')) {
            return;
        }

        $services = [
            [
                'name' => 'جلسه آموزش مقدماتی',
                'description' => 'یک جلسه حضوری ۶۰ دقیقه‌ای برای اطاعت پایه.',
                'base_price' => 1_200_000,
                'duration_minutes' => 60,
                'training_surcharges' => [
                    'beginner' => 0,
                    'advanced' => 250_000,
                    'behavioral' => 350_000,
                    'care' => 100_000,
                    'other' => 0,
                ],
            ],
            [
                'name' => 'اصلاح رفتار تخصصی',
                'description' => 'تمرکز روی پارس، کشیدن قلاده و اضطراب.',
                'base_price' => 1_800_000,
                'duration_minutes' => 90,
                'training_surcharges' => [
                    'beginner' => 0,
                    'advanced' => 200_000,
                    'behavioral' => 0,
                    'care' => 0,
                    'other' => 150_000,
                ],
            ],
            [
                'name' => 'جلسه کوتاه ۳۰ دقیقه',
                'description' => 'مرور سریع فرمان‌ها.',
                'base_price' => 700_000,
                'duration_minutes' => 30,
                'training_surcharges' => [
                    'beginner' => 0,
                    'advanced' => 100_000,
                    'behavioral' => 150_000,
                    'care' => 0,
                    'other' => 0,
                ],
            ],
        ];

        foreach ($services as $row) {
            ProviderService::query()->updateOrCreate(
                [
                    'user_id' => $coach->id,
                    'service_type' => 'coach',
                    'name' => $row['name'],
                ],
                [
                    'description' => $row['description'],
                    'specialty_id' => $specialtyId,
                    'base_price' => $row['base_price'],
                    'currency' => 'IRT',
                    'duration_minutes' => $row['duration_minutes'],
                    'training_surcharges' => $row['training_surcharges'],
                    'extras' => null,
                    'is_active' => true,
                ]
            );
        }

        ProviderService::query()->updateOrCreate(
            [
                'user_id' => $coach->id,
                'service_type' => 'coach',
                'name' => 'خدمت غیرفعال (تست)',
            ],
            [
                'description' => 'نباید در ویزارد رزرو دیده شود.',
                'specialty_id' => $specialtyId,
                'base_price' => 500_000,
                'currency' => 'IRT',
                'duration_minutes' => 45,
                'is_active' => false,
            ]
        );
    }

    private function seedClinicServices(User $clinic, $specialtyId): void
    {
        if (! Schema::hasTable('provider_services')) {
            return;
        }

        ProviderService::query()->updateOrCreate(
            ['user_id' => $clinic->id, 'service_type' => 'clinic', 'name' => 'ویزیت عمومی'],
            [
                'description' => 'معاینه عمومی دامپزشکی.',
                'specialty_id' => $specialtyId,
                'base_price' => 850_000,
                'currency' => 'IRT',
                'duration_minutes' => 45,
                'is_active' => true,
            ]
        );

        ProviderService::query()->updateOrCreate(
            ['user_id' => $clinic->id, 'service_type' => 'clinic', 'name' => 'واکسیناسیون'],
            [
                'description' => 'نوبت واکسن سالانه.',
                'specialty_id' => $specialtyId,
                'base_price' => 650_000,
                'currency' => 'IRT',
                'duration_minutes' => 30,
                'is_active' => true,
            ]
        );
    }

    private function seedBreederServices(User $breeder, $specialtyId): void
    {
        if (! Schema::hasTable('provider_services')) {
            return;
        }

        ProviderService::query()->updateOrCreate(
            ['user_id' => $breeder->id, 'service_type' => 'breeder', 'name' => 'مشاوره انتخاب توله'],
            [
                'description' => 'جلسه مشاوره حضوری برای انتخاب توله مناسب.',
                'specialty_id' => $specialtyId,
                'base_price' => 500_000,
                'currency' => 'IRT',
                'duration_minutes' => 60,
                'is_active' => true,
            ]
        );
    }

    private function seedAvailability(User $provider): void
    {
        if (! Schema::hasTable('provider_availability_settings')) {
            return;
        }

        ProviderAvailabilitySetting::query()->updateOrCreate(
            ['user_id' => $provider->id],
            [
                'min_notice_minutes' => 60,
                'max_advance_days' => 45,
                'slot_interval_minutes' => 30,
            ]
        );

        ProviderAvailabilityRule::query()->where('user_id', $provider->id)->delete();

        foreach ([0, 1, 2, 3, 4, 5, 6] as $weekday) {
            ProviderAvailabilityRule::query()->create([
                'user_id' => $provider->id,
                'weekday' => $weekday,
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'is_break' => false,
            ]);
            ProviderAvailabilityRule::query()->create([
                'user_id' => $provider->id,
                'weekday' => $weekday,
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'is_break' => true,
            ]);
        }
    }

    private function seedLegacyPriceFallback(User $provider): void
    {
        if (! Schema::hasTable('provider_service_prices')) {
            return;
        }

        $type = 'coach';
        if ($provider->hasRole('clinic')) {
            $type = 'clinic';
        } elseif ($provider->hasRole('breeder')) {
            $type = 'breeder';
        }

        ProviderServicePrice::query()->updateOrCreate(
            ['user_id' => $provider->id, 'service_type' => $type],
            [
                'base_price' => $type === 'coach' ? 1_000_000 : ($type === 'clinic' ? 800_000 : 500_000),
                'training_surcharges' => $type === 'coach'
                    ? ['beginner' => 0, 'advanced' => 200_000, 'behavioral' => 300_000]
                    : null,
            ]
        );
    }

    private function seedOpenSession(User $coach): void
    {
        if (! Schema::hasTable('open_sessions')) {
            return;
        }

        OpenSession::query()->updateOrCreate(
            [
                'user_id' => $coach->id,
                'title' => 'کلاس گروهی اطاعت — دمو',
            ],
            [
                'description' => 'جلسه باز دمو برای تست اپلای و پیشنهاد قیمت.',
                'category' => 'training',
                'required_role' => 'coach',
                'location' => 'تهران، پارک ملت',
                'is_online' => false,
                'preferred_date' => now(config('app.timezone', 'Asia/Tehran'))->addDays(5)->toDateString(),
                'preferred_slot' => '10:00',
                'flexible_schedule' => false,
                'duration_minutes' => 90,
                'participants_needed' => 3,
                'participants_filled' => 0,
                'budget_min' => 500_000,
                'budget_max' => 1_500_000,
                'deadline_at' => now()->addDays(4),
                'status' => 'PUBLISHED',
            ]
        );
    }
}
