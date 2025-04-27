<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use App\Models\City;
use App\Models\Breed;
use App\Models\Specialty;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // دریافت یک یا چند نمونه از شهرها، نژادها و تخصص‌ها
        $city = City::inRandomOrder()->first();  // انتخاب تصادفی یک شهر
        $breed = Breed::inRandomOrder()->first();  // انتخاب تصادفی یک نژاد
        $specialty = Specialty::inRandomOrder()->first();  // انتخاب تصادفی یک تخصص

        // ایجاد داده‌های نمونه برای جدول coaches
        Coach::create([
            'city_id' => $city->id,  // ارتباط با جدول cities
            'breed_id' => $breed->id,  // ارتباط با جدول breeds
            'specialty_id' => $specialty->id,  // ارتباط با جدول specialties
            'name' => 'مربی ' . $this->generateRandomName(),
            'description' => 'توضیحات نمونه برای مربی.',
        ]);
    }

    /**
     * ایجاد نام تصادفی برای مربی
     *
     * @return string
     */
    private function generateRandomName()
    {
        $names = ['Ali', 'Reza', 'Sara', 'Mina', 'Mohammad'];
        return $names[array_rand($names)];
    }
}
