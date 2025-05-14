<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'title'       => 'چرا دوبرمن بهترین سگ نگهبان است؟',
                'excerpt'     => 'دوبرمن یکی از هوشمندترین سگ‌ها برای نگهبانی است...',
                'body'        => 'دوبرمن به‌خاطر هوش بالا، سرعت، قدرت و وفاداری خود شناخته شده است. این سگ‌ها قابلیت یادگیری سریع دستورات، واکنش مناسب در موقعیت‌های تهدیدآمیز و محافظت از خانواده را دارند. همچنین ظاهر قدرتمند و جسم عضلانی آن‌ها به‌طور طبیعی از ورود غریبه‌ها جلوگیری می‌کند.',
                'image'       => 'doberman.jpg',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'نگهداری و تربیت سگ در خانه',
                'excerpt'     => 'آیا می‌خواهید یک سگ خانگی تربیت‌شده داشته باشید؟',
                'body'        => 'برای نگهداری سگ در خانه باید نکات مهمی مانند تغذیه، آموزش، ورزش منظم و محیط مناسب را در نظر بگیرید. همچنین برقراری ارتباط مناسب با سگ از طریق بازی و تمرین‌های مشترک به بهبود رفتار آن کمک می‌کند.',
                'image'       => 'dog.jpg',
                'created_at'  => Carbon::now()->subDays(1),
                'updated_at'  => Carbon::now()->subDays(1),
            ],
            [
                'title'       => '۵ نکته برای انتخاب غذای مناسب سگ',
                'excerpt'     => 'تغذیه مناسب نقش مهمی در سلامت سگ دارد.',
                'body'        => 'انتخاب غذای مناسب بستگی به سن، نژاد و سطح فعالیت سگ دارد. به‌علاوه توجه به کیفیت پروتئین، میزان چربی و مواد معدنی مانند کلسیم و فسفر ضروری است. مشورت با دامپزشک نیز همیشه توصیه می‌شود.',
                'image'       => 'food.jpg',
                'created_at'  => Carbon::now()->subDays(2),
                'updated_at'  => Carbon::now()->subDays(2),
            ],
        ]);
    }
}
