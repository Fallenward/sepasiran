<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsTableSeeder::class,
            CitiesTableSeeder::class,
            SpecialtySeeder::class,
            BreedSeeder::class,
            DogAttributeSeeder::class,
            CategorySeeder::class,
            SkillSeeder::class,
            SlidersTableSeeder::class,
            ServiceSeeder::class,
            DogSeeder::class,
            ProductSeeder::class,
            InsuranceSeeder::class,
            BlogSeeder::class,
            BlogRatingSeeder::class,
            CourseSeeder::class,
            CourseDemoSeeder::class,
            ReviewSeeder::class,
            CartSeeder::class,
            BookingSeeder::class,
            TransactionSeeder::class,
            PermissionSeeder::class,
            UsersTableSeeder::class,
            DemoScenarioSeeder::class,
            BookingDemoSeeder::class,
        ]);
    }
}
