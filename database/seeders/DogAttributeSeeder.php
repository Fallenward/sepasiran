<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DogAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        if (Schema::hasTable('dog_colors')) {
            $colors = [
                'مشکی', 'سفید', 'قهوه‌ای', 'طلایی', 'کرمی', 'کرم', 'خاکستری',
                'مشکی-قهوه‌ای', 'سفید-خاکستری', 'نارنجی', 'قرمز', 'آبی',
            ];
            foreach ($colors as $i => $name) {
                DB::table('dog_colors')->updateOrInsert(
                    ['name' => $name],
                    ['is_active' => true, 'sort_order' => $i + 1, 'updated_at' => $now, 'created_at' => $now]
                );
            }
            if (Schema::hasTable('dogs')) {
                foreach (DB::table('dogs')->whereNotNull('color')->distinct()->pluck('color') as $name) {
                    $name = trim((string) $name);
                    if ($name === '') {
                        continue;
                    }
                    DB::table('dog_colors')->updateOrInsert(
                        ['name' => $name],
                        ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                    );
                }
            }
        }

        if (Schema::hasTable('dog_eye_colors')) {
            $eyes = ['قهوه‌ای', 'آبی', 'کهربایی', 'سبز', 'مشکی', 'دو رنگ'];
            foreach ($eyes as $i => $name) {
                DB::table('dog_eye_colors')->updateOrInsert(
                    ['name' => $name],
                    ['is_active' => true, 'sort_order' => $i + 1, 'updated_at' => $now, 'created_at' => $now]
                );
            }
            if (Schema::hasTable('dogs')) {
                foreach (DB::table('dogs')->whereNotNull('eye_color')->distinct()->pluck('eye_color') as $name) {
                    $name = trim((string) $name);
                    if ($name === '') {
                        continue;
                    }
                    DB::table('dog_eye_colors')->updateOrInsert(
                        ['name' => $name],
                        ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                    );
                }
            }
        }

        if (Schema::hasTable('dog_cover_types')) {
            $covers = [
                ['name' => 'کوتاه', 'code' => 'short'],
                ['name' => 'متوسط', 'code' => 'medium'],
                ['name' => 'بلند', 'code' => 'long'],
                ['name' => 'پشمی', 'code' => 'pashmi'],
                ['name' => 'مجعد', 'code' => 'curly'],
                ['name' => 'سیمی', 'code' => 'wire'],
            ];
            foreach ($covers as $i => $row) {
                DB::table('dog_cover_types')->updateOrInsert(
                    ['name' => $row['name']],
                    ['code' => $row['code'], 'is_active' => true, 'sort_order' => $i + 1, 'updated_at' => $now, 'created_at' => $now]
                );
            }
            if (Schema::hasTable('dogs')) {
                foreach (DB::table('dogs')->whereNotNull('cover_type')->distinct()->pluck('cover_type') as $name) {
                    $name = trim((string) $name);
                    if ($name === '') {
                        continue;
                    }
                    DB::table('dog_cover_types')->updateOrInsert(
                        ['name' => $name],
                        ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                    );
                }
            }
        }
    }
}