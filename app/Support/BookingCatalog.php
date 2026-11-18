<?php

namespace App\Support;

class BookingCatalog
{
    public static function serviceTypes(): array
    {
        return [
            ['key' => 'coach', 'label' => 'مربی', 'default_price' => 1200000],
            ['key' => 'clinic', 'label' => 'کلینیک', 'default_price' => 850000],
            ['key' => 'breeder', 'label' => 'پرورش‌دهنده', 'default_price' => 500000],
            ['key' => 'insurance', 'label' => 'بیمه', 'default_price' => 1500000],
        ];
    }

    public static function trainingTypes(): array
    {
        return [
            ['key' => 'beginner', 'label' => 'آموزش مقدماتی'],
            ['key' => 'advanced', 'label' => 'تربیت پیشرفته'],
            ['key' => 'behavioral', 'label' => 'اصلاح رفتار'],
            ['key' => 'care', 'label' => 'آموزش نگهداری'],
            ['key' => 'other', 'label' => 'سایر'],
        ];
    }

    public static function slotTemplate(): array
    {
        return ['09:00', '11:00', '14:30', '16:00', '18:00', '20:00'];
    }

    public static function priceFor(string $serviceType): int
    {
        foreach (static::serviceTypes() as $row) {
            if ($row['key'] === $serviceType) {
                return (int) $row['default_price'];
            }
        }

        return 0;
    }

    public static function roleFor(string $serviceType): string
    {
        return match ($serviceType) {
            'coach' => 'coach',
            'clinic', 'insurance' => 'clinic',
            'breeder' => 'breeder',
            default => 'coach',
        };
    }

    public static function keys(): array
    {
        return array_column(static::serviceTypes(), 'key');
    }

    public static function trainingKeys(): array
    {
        return array_column(static::trainingTypes(), 'key');
    }
}