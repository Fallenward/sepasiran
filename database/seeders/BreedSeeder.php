<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreedSeeder extends Seeder
{
    public function run(): void
    {
        $breeds = [
            ['name' => 'ژرمن شپرد', 'description' => 'هوشمند، وفادار و مناسب نگهبانی و خانواده.'],
            ['name' => 'هاسکی سیبری', 'description' => 'پرانرژی، اجتماعی و عاشق فعالیت در فضای باز.'],
            ['name' => 'لابرادور', 'description' => 'مهربان، آموزش‌پذیر و عالی برای خانواده.'],
            ['name' => 'گلدن رتریور', 'description' => 'آرام، دوست‌داشتنی و مناسب کودکان.'],
            ['name' => 'بولداگ فرانسوی', 'description' => 'جمع‌وجور، بازیگوش و مناسب آپارتمان.'],
            ['name' => 'پامرانین', 'description' => 'کوچک، پرتحرک و مناسب نگهداری خانگی.'],
            ['name' => 'شیبا اینو', 'description' => 'مستقل، تمیز و با شخصیت خاص.'],
            ['name' => 'روتوایلر', 'description' => 'قدرتمند، نگهبان و نیازمند آموزش منظم.'],
            ['name' => 'مالینویز', 'description' => 'ورزشی، سریع و مناسب کار و تمرین حرفه‌ای.'],
            ['name' => 'پاگ', 'description' => 'صمیمی، آرام و مناسب زندگی شهری.'],
            ['name' => 'شیتزو', 'description' => 'لطیف، همراه و مناسب خانه.'],
            ['name' => 'داکس‌هوند', 'description' => 'کنجکاو، شجاع و با فرم بدنی خاص.'],
        ];

        foreach ($breeds as $breed) {
            DB::table('breeds')->updateOrInsert(
                ['name' => $breed['name']],
                [
                    'description' => $breed['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}