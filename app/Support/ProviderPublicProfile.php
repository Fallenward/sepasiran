<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Collection;

class ProviderPublicProfile
{
    /**
     * @return array{
     *   full_name:string,
     *   city_name:?string,
     *   specialties:Collection<int,string>,
     *   rating_avg:float,
     *   rating_count:int,
     *   completed_sessions:int,
     *   is_verified:bool,
     *   bio:?string,
     *   phone:?string
     * }
     */
    public static function summarize(User $provider, ?int $completedSessions = null): array
    {
        $fullName = trim(($provider->name ?? '').' '.($provider->family ?? ''));
        $specialties = collect($provider->speciality ?? [])
            ->map(fn ($s) => is_string($s) ? $s : ($s->name ?? null))
            ->filter()
            ->unique()
            ->values();

        $avg = null;
        $count = null;
        if (isset($provider->ratings_avg_value)) {
            $avg = (float) $provider->ratings_avg_value;
            $count = (int) ($provider->ratings_count ?? 0);
        } elseif ($provider->relationLoaded('ratings')) {
            $count = $provider->ratings->count();
            $avg = $count > 0 ? (float) $provider->ratings->avg('value') : 0.0;
        } else {
            $count = (int) $provider->ratings()->count();
            $avg = $count > 0 ? (float) $provider->ratings()->avg('value') : 0.0;
        }

        $cityName = null;
        if ($provider->relationLoaded('city') && $provider->city) {
            $cityName = $provider->city->name ?? null;
        } elseif (! empty($provider->city_id)) {
            $cityName = optional(\App\Models\City::query()->find($provider->city_id))->name;
        }

        if ($completedSessions === null) {
            $completedSessions = ProviderBookingResume::forProvider((int) $provider->id, 1)['total'];
        }

        return [
            'full_name' => $fullName !== '' ? $fullName : 'کاربر',
            'city_name' => $cityName,
            'specialties' => $specialties,
            'rating_avg' => round($avg, 1),
            'rating_count' => $count,
            'completed_sessions' => (int) $completedSessions,
            'is_verified' => (bool) ($provider->is_verified ?? false),
            'bio' => filled($provider->bio) ? (string) $provider->bio : null,
            'phone' => filled($provider->phone) ? (string) $provider->phone : null,
        ];
    }
}