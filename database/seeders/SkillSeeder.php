<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['گوش دادن', 'دستورات پایه', 'جستجو', 'حفاظت'] as $skill) {
            DB::table('skills')->updateOrInsert(
                ['title' => $skill],
                [
                    'description' => 'توضیح ' . $skill,
                    'is_active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}