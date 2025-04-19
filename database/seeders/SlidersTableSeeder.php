<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Source folder for images
        $sourceFolder = base_path('database/seeders/images');

        // Destination folder in public directory
        $destinationFolder = public_path('images');

        // Create directory if it doesn't exist
        if (!File::exists($destinationFolder)) {
            File::makeDirectory($destinationFolder, 0755, true);
        }

        // Sample data with EXACT fields you specified
        $sliders = [
            [
                'title' => 'Slider 1',
                'image' => 'images/slider.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Slider 2',
                'image' => 'images/slider.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Slider 3',
                'image' => 'images/slider.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert into database
        DB::table('sliders')->insert($sliders);
    }

    /**
     * Copy image to public directory and return path
     */
    private function copyImage(string $sourcePath, string $destinationFolder): string
    {
        if (!File::exists($sourcePath)) {
            throw new \Exception("Image not found: $sourcePath");
        }

        $fileName = basename($sourcePath);
        $destinationPath = $destinationFolder . '/' . $fileName;

        File::copy($sourcePath, $destinationPath);

        return 'images/sliders/' . $fileName;
    }
}
