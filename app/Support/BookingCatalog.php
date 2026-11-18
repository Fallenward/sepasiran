<?php

namespace App\Support;

class BookingCatalog
{
    public static function serviceTypes(): array
    {
        return [
            ['key' => 'coach', 'label' => 'مربی', 'default_price' => 1200000, 'role' => 'coach'],
            ['key' => 'clinic', 'label' => 'کلینیک', 'default_price' => 850000, 'role' => 'clinic'],
            ['key' => 'breeder', 'label' => 'پرورش‌دهنده', 'default_price' => 500000, 'role' => 'breeder'],
            ['key' => 'insurance', 'label' => 'بیمه', 'default_price' => 1500000, 'role' => 'clinic'],
        ];
    }

    public static function trainingTypes(): array
    {
        return [
            ['key' => 'beginner', 'label' => 'آموزش مقدماتی', 'surcharge' => 0],
            ['key' => 'advanced', 'label' => 'تربیت پیشرفته', 'surcharge' => 200000],
            ['key' => 'behavioral', 'label' => 'اصلاح رفتار', 'surcharge' => 300000],
            ['key' => 'care', 'label' => 'آموزش نگهداری', 'surcharge' => 0],
            ['key' => 'other', 'label' => 'سایر', 'surcharge' => 0],
        ];
    }

    public static function fulfillmentModes(): array
    {
        return [
            ['key' => 'direct', 'label' => 'ارسال مستقیم به ارائه‌دهنده'],
            ['key' => 'open', 'label' => 'درخواست باز — ارائه‌دهندگان پیشنهاد بدهند'],
        ];
    }

    public static function statuses(): array
    {
        return ['PENDING', 'OPEN', 'ACCEPTED', 'CONFIRMED', 'CANCELLED', 'COMPLETED'];
    }

    public static function slotTemplate(): array
    {
        return ['09:00', '11:00', '14:30', '16:00', '18:00', '20:00'];
    }

    public static function pricingMeta(): array
    {
        return [
            'tax_rate' => 0,
            'open_listing_fee' => 50000,
            'currency' => 'IRT',
        ];
    }

    public static function openListingFee(): int
    {
        return (int) static::pricingMeta()['open_listing_fee'];
    }

    public static function taxRate(): float
    {
        return (float) static::pricingMeta()['tax_rate'];
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

    public static function trainingSurcharge(?string $trainingType): int
    {
        if (!$trainingType) {
            return 0;
        }

        foreach (static::trainingTypes() as $row) {
            if ($row['key'] === $trainingType) {
                return (int) ($row['surcharge'] ?? 0);
            }
        }

        return 0;
    }

    public static function modeFee(string $fulfillmentMode): int
    {
        return $fulfillmentMode === 'open' ? static::openListingFee() : 0;
    }

    /**
     * @return array{currency:string,price:int,surcharge:int,mode_fee:int,tax:int,discount:int,total:int,breakdown:array<int,array{label:string,amount:int}>}
     */
    public static function quote(string $serviceType, string $fulfillmentMode, ?string $trainingType = null, int $discount = 0): array
    {
        $base = static::priceFor($serviceType);
        $surcharge = $serviceType === 'coach' ? static::trainingSurcharge($trainingType) : 0;
        $modeFee = static::modeFee($fulfillmentMode);
        $subtotal = $base + $surcharge;
        $tax = (int) round($subtotal * static::taxRate());
        $total = max(0, $subtotal + $tax + $modeFee - $discount);

        $breakdown = [
            ['label' => 'پایه خدمت', 'amount' => $base],
        ];
        if ($surcharge > 0) {
            $breakdown[] = ['label' => 'نوع آموزش', 'amount' => $surcharge];
        }
        if ($modeFee > 0) {
            $breakdown[] = ['label' => 'کارمزد درخواست باز', 'amount' => $modeFee];
        }
        if ($tax > 0) {
            $breakdown[] = ['label' => 'مالیات', 'amount' => $tax];
        }
        if ($discount > 0) {
            $breakdown[] = ['label' => 'تخفیف', 'amount' => -$discount];
        }

        return [
            'currency' => 'IRT',
            'price' => $subtotal,
            'surcharge' => $surcharge,
            'mode_fee' => $modeFee,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'breakdown' => $breakdown,
        ];
    }

    public static function roleFor(string $serviceType): string
    {
        foreach (static::serviceTypes() as $row) {
            if ($row['key'] === $serviceType) {
                return (string) ($row['role'] ?? 'coach');
            }
        }

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

    public static function fulfillmentKeys(): array
    {
        return array_column(static::fulfillmentModes(), 'key');
    }

    public static function addDogUrl(): string
    {
        return '/breeder/dashboard-create';
    }
}
