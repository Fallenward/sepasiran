<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GenralHelpers
{
    public static function limitTextToTwoLines($text, $maxLength = 7)
    {

        $words = explode(' ', $text);


        $line1 = implode(' ', array_slice($words, 0, $maxLength));
        $line2 = implode(' ', array_slice($words, $maxLength, $maxLength));

        if (count($words) > ($maxLength * 2)) {
            $line2 .= '...';
        }

        return $line1 . ' ' . $line2;
    }

    public function copyImage(string $sourcePath, string $destinationFolder): ?string
    {
        // Check if the source file exists
        if (!File::exists($sourcePath)) {
            return null;
        }

        // Get the file name
        $fileName = basename($sourcePath);

        // Full destination path
        $destinationPath = $destinationFolder . '/' . $fileName;

        // Copy the file to the destination folder
        Storage::put($destinationPath, File::get($sourcePath));

        // Return the file name for database storage
        return $fileName;
    }
}
