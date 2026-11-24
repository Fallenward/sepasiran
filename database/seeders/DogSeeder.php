<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DogSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $breedRows = DB::table('breeds')->orderBy('id')->get();
        $cityIds = DB::table('cities')->pluck('id');
        if (!$userId || $breedRows->isEmpty()) {
            return;
        }

        $demo = [
            ['title' => 'رکس', 'breed' => 'ژرمن شپرد', 'age' => 8, 'color' => 'مشکی-قهوه‌ای', 'image' => 'images/demo/dogs/dog-01.jpg', 'size' => 'large'],
            ['title' => 'لونا', 'breed' => 'هاسکی سیبری', 'age' => 14, 'color' => 'سفید-خاکستری', 'image' => 'images/demo/dogs/dog-02.jpg', 'size' => 'large'],
            ['title' => 'مکس', 'breed' => 'لابرادور', 'age' => 10, 'color' => 'کرمی', 'image' => 'images/demo/dogs/dog-03.jpg', 'size' => 'large'],
            ['title' => 'بِلا', 'breed' => 'گلدن رتریور', 'age' => 12, 'color' => 'طلایی', 'image' => 'images/demo/dogs/dog-04.jpg', 'size' => 'large'],
            ['title' => 'تامی', 'breed' => 'بولداگ فرانسوی', 'age' => 6, 'color' => 'کرم', 'image' => 'images/demo/dogs/dog-05.jpg', 'size' => 'small'],
            ['title' => 'نیکی', 'breed' => 'پامرانین', 'age' => 9, 'color' => 'نارنجی', 'image' => 'images/demo/dogs/dog-06.jpg', 'size' => 'small'],
            ['title' => 'آکی', 'breed' => 'شیبا اینو', 'age' => 16, 'color' => 'قرمز', 'image' => 'images/demo/dogs/dog-07.jpg', 'size' => 'medium'],
            ['title' => 'کاسی', 'breed' => 'پاگ', 'age' => 7, 'color' => 'کرمی', 'image' => 'images/demo/dogs/dog-08.jpg', 'size' => 'small'],
            ['title' => 'راکی', 'breed' => 'روتوایلر', 'age' => 11, 'color' => 'مشکی', 'image' => 'images/demo/dogs/dog-01.jpg', 'size' => 'large'],
            ['title' => 'میلو', 'breed' => 'مالینویز', 'age' => 13, 'color' => 'قهوه‌ای', 'image' => 'images/demo/dogs/dog-05.jpg', 'size' => 'large'],
            ['title' => 'سوزی', 'breed' => 'شیتزو', 'age' => 5, 'color' => 'سفید', 'image' => 'images/demo/dogs/dog-06.jpg', 'size' => 'small'],
            ['title' => 'جک', 'breed' => 'داکس‌هوند', 'age' => 15, 'color' => 'قهوه‌ای', 'image' => 'images/demo/dogs/dog-02.jpg', 'size' => 'medium'],
        ];

        DB::table('dogs')->delete();

        foreach ($demo as $i => $row) {
            $breed = $breedRows->firstWhere('name', $row['breed']) ?? $breedRows[$i % $breedRows->count()];
            $cityId = $cityIds->isNotEmpty() ? $cityIds[$i % $cityIds->count()] : null;

            DB::table('dogs')->insert([
                'user_id' => $userId,
                'title' => $row['title'],
                'image' => $row['image'],
                'breed' => $row['breed'],
                'color' => $row['color'],
                'status' => 'available',
                'age' => $row['age'],
                'birth_date' => Carbon::now()->subMonths($row['age'])->toDateString(),
                'cover_type' => 'short',
                'eye_color' => 'قهوه‌ای',
                'shajare' => $i % 2 === 0,
                'breed_id' => $breed->id,
                'father_name' => 'سگ پدر',
                'father_breed_id' => $breed->id,
                'mother_name' => 'سگ مادر',
                'mother_breed_id' => $breed->id,
                'city_id' => $cityId,
                'size' => $row['size'],
                'created_at' => now()->subDays(12 - $i),
                'updated_at' => now(),
            ]);
        }
    }
}