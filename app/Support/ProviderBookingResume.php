<?php

namespace App\Support;

use App\Models\Booking;
use Illuminate\Support\Collection;

class ProviderBookingResume
{
    /**
     * Privacy-safe public resume of sessions a provider has completed.
     *
     * @return array{total:int,items:Collection<int,array<string,mixed>>}
     */
    public static function forProvider(int $providerId, int $limit = 12): array
    {
        $query = Booking::query()
            ->where('provider_id', $providerId)
            ->where('status', 'COMPLETED')
            ->with(['breed:id,name', 'city:id,name', 'dog:id,title,breed_id'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('id');

        $total = (clone $query)->count();
        $rows = $query->take($limit)->get();

        $serviceLabels = collect(BookingCatalog::serviceTypes())->pluck('label', 'key');
        $trainingLabels = collect(BookingCatalog::trainingTypes())->pluck('label', 'key');

        $items = $rows->map(function (Booking $booking) use ($serviceLabels, $trainingLabels) {
            $when = $booking->appointment_date
                ?? $booking->preferred_date
                ?? $booking->booking_date
                ?? $booking->created_at;

            $serviceKey = (string) ($booking->service_type ?? '');
            $trainingKey = (string) ($booking->training_type ?? '');

            $breedName = $booking->breed?->name;
            if (! $breedName && $booking->dog && method_exists($booking->dog, 'breedName')) {
                $breedName = $booking->dog->breedName();
            }

            $fallbackTitle = 'جلسه';
            $titleParts = array_values(array_filter([
                $serviceLabels[$serviceKey] ?? ($serviceKey !== '' ? $serviceKey : $fallbackTitle),
                $trainingKey !== '' ? ($trainingLabels[$trainingKey] ?? $trainingKey) : null,
            ]));

            return [
                'title' => implode(' · ', $titleParts),
                'date' => $when ? $when->timezone(config('app.timezone'))->format('Y/m/d') : null,
                'slot' => $booking->slot ?: $booking->preferred_slot,
                'city' => $booking->city?->name,
                'breed' => $breedName,
                'dog' => $booking->dog?->title,
                'status' => 'COMPLETED',
                'status_label' => 'انجام‌شده',
            ];
        });

        return [
            'total' => $total,
            'items' => $items,
        ];
    }
}