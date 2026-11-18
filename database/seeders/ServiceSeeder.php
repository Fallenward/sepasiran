<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $staticTitles = [
            'انتخاب مربی',
            'تهیه سگ',
            'دوره های آنلاین',
            'فروشگاه ها',
            'کلینیک ها و بیمه حیوانات',
            'پرورش دهنده',
        ];

        foreach ($staticTitles as $i => $title) {
            $slug = Str::slug($title, '-') ?: 'service-' . ($i + 1);

            DB::table('services')->updateOrInsert(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => Str::limit(fake()->paragraph(3, true), 200, '…'),
                    'image' => 'images/service-' . ($i + 1) . '.png',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}