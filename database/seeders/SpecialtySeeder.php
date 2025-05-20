<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        // You can fetch an existing user_id dynamically if needed
        $userId = DB::table('users')->value('id') ?? 1;

        DB::table('specialties')->insert([
            [
                'name'       => 'آموزش فرمان‌ها',
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'مهارت‌های فردی',
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more specialties if needed
        ]);
    }
}
