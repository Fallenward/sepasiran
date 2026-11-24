<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlidersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sliders')->delete();

        $sliders = [
            [
                'title' => 'بازار آرام برای همراه شما',
                'image' => 'images/demo/hero/hero-01.jpg',
                'link' => '/dog-supply',
            ],
            [
                'title' => 'رزرو مربی و کلینیک',
                'image' => 'images/demo/hero/hero-02.jpg',
                'link' => '/coaches',
            ],
            [
                'title' => 'فروشگاه مراقبت و لوازم',
                'image' => 'images/demo/hero/hero-03.jpg',
                'link' => '/stores',
            ],
        ];

        foreach ($sliders as $slider) {
            DB::table('sliders')->insert([
                'title' => $slider['title'],
                'image' => $slider['image'],
                'link' => $slider['link'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}