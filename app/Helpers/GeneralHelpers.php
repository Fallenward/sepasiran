<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GeneralHelpers
{
    public static function limitTextToTwoLines($text, $maxLength = 7)
    {
        $words = explode(' ', $text);

        $line1 = implode(' ', array_slice($words, 0, $maxLength));
        $line2 = implode(' ', array_slice($words, $maxLength, $maxLength));

        if (count($words) > ($maxLength * 2)) {
            $line2 .= '...';
        }

        return $line1.' '.$line2;
    }

    public function copyImage(string $sourcePath, string $destinationFolder): ?string
    {
        if (! File::exists($sourcePath)) {
            return null;
        }

        $fileName = basename($sourcePath);
        $destinationPath = $destinationFolder.'/'.$fileName;
        Storage::put($destinationPath, File::get($sourcePath));

        return $fileName;
    }

    public static function shouldShowMenu($route, $user)
    {
        $clinicHidden = ['admin.sliders.index', 'admin.transactions.index', 'admin.clinics.index', 'admin.breeders.index', 'admin.coaches.index'];
        $breederHidden = ['admin.coaches.index', 'admin.clinics.index', 'admin.breeders.index', 'admin.courses.index', 'coming.soon'];
        $coachHidden = ['admin.blogs.index', 'admin.dogs.index', 'admin.coaches.index', 'admin.clinics.index', 'admin.breeders.index', 'admin.courses.index', 'coming.soon'];

        if ($user->hasRole('clinic') && in_array($route, $clinicHidden)) {
            return false;
        }
        if ($user->hasRole('breeder') && in_array($route, $breederHidden)) {
            return false;
        }
        if ($user->hasRole('coach') && in_array($route, $coachHidden)) {
            return false;
        }

        return true;
    }
}
