<?php

namespace Database\Seeders;

use App\Helpers\GeneralHelpers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Destination folder for storing blog images
        $imageDestinationFolder = 'public/settings';

        // Ensure the destination folder exists
        if (!Storage::exists($imageDestinationFolder)) {
            Storage::makeDirectory($imageDestinationFolder);
        }
        $generalHelper = new GeneralHelpers();


        DB::table('settings')->insert([
            'color_setting_primary' => '#4FBF78',
            'color_setting_secondary' => '#D4B56A',
            'color_setting_third' => '#E8F0EB',
            'color_setting_fourth' => '#0C1210',
            'sms_setting_username' => '',
            'sms_setting_password' => '',
            'footer_setting_social_twitter' => '',
            'footer_setting_social_instagram' => '',
            'footer_setting_social_telegram' => '',
            'footer_setting_social_linkedin' => '',
            'sms_setting_sender' => '',
            'sms_setting_apikey' => '',
            'sms_setting_secretkey' => '',

            'footer_setting_useful_links' => json_encode([
                ['title' => 'دوره ها در یک نگاه', 'url' => '#'],
                ['title' => 'نمونه کلاس ها', 'url' => '#'],
                ['title' => 'شرایط و قوانین', 'url' => '#'],
                ['title' => 'نرم افزار های مورد نیاز', 'url' => '#'],
            ]),

            'footer_setting_menu' => json_encode([
                ['title' => 'کادر دبیران سپاس ایران', 'url' => '#'],
                ['title' => 'تماس با سپاس ایران', 'url' => '#'],
                ['title' => 'درباره ی سپاس ایران', 'url' => '#'],
                ['title' => 'بلاگ سپاس ایران', 'url' => '#'],
            ]),

            'footer_setting_enamad_logo' => url('images/' . $generalHelper->copyImage('database/seeders/images/enamad.png', $imageDestinationFolder)),
            'footer_setting_samandehi_logo' => url('images/' . $generalHelper->copyImage('database/seeders/images/samandehi.png', $imageDestinationFolder)),

            'footer_setting_about' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد کتابهای زیادی در شصت و سه درصد گذشته حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد',
            'footer_setting_logo' => 'images/' . $generalHelper->copyImage('database/seeders/images/default-logo.svg', $imageDestinationFolder),
            'fav_icon' => url('images/' . $generalHelper->copyImage('database/seeders/images/fav.png', $imageDestinationFolder)),

            'about_setting_about' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد کتابهای زیادی در شصت و سه درصد گذشته حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد',
            'about_setting_img' => url('images/' . $generalHelper->copyImage('database/seeders/images/about-us.png', $imageDestinationFolder)),

            'comment_settings_type' => 'realtime',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
