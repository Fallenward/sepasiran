<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\ProviderAvailabilityException;
use App\Models\ProviderAvailabilityRule;
use App\Models\ProviderAvailabilitySetting;
use App\Models\ProviderService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BookingAvailabilityService
{
    /**
     * @return list<array{time:string,available:bool,label?:string}>
     */
    public function slotsFor(int $providerId, Carbon $date, ?ProviderService $service = null): array
    {
        $tz = config('app.timezone', 'Asia/Tehran');
        $day = $date->copy()->timezone($tz)->startOfDay();
        $settings = ProviderAvailabilitySetting::query()->find($providerId);
        $minNotice = (int) ($settings?->min_notice_minutes ?? 120);
        $maxAdvance = (int) ($settings?->max_advance_days ?? 60);
        $duration = (int) ($service?->duration_minutes ?: 60);
        $interval = (int) ($settings?->slot_interval_minutes ?: $duration);
        if ($interval < 15) {
            $interval = 15;
        }

        $today = Carbon::now($tz)->startOfDay();
        if ($day->lt($today) || $day->gt($today->copy()->addDays($maxAdvance))) {
            return [];
        }

        $exceptions = ProviderAvailabilityException::query()
            ->where('user_id', $providerId)
            ->whereDate('date', $day->toDateString())
            ->get();

        if ($exceptions->contains(fn ($e) => $e->all_day && in_array($e->kind, ['unavailable', 'block'], true))) {
            return [];
        }

        $weekday = (int) $day->dayOfWeek; // 0 Sun .. 6 Sat
        $rules = ProviderAvailabilityRule::query()
            ->where('user_id', $providerId)
            ->where('weekday', $weekday)
            ->orderBy('start_time')
            ->get();

        $work = $rules->where('is_break', false)->values();
        $breaks = $rules->where('is_break', true)->values();

        // Fallback: catalog template if no rules configured
        if ($work->isEmpty()) {
            return $this->catalogFallback($providerId, $day, $minNotice, $tz);
        }

        $candidates = [];
        foreach ($work as $period) {
            $start = Carbon::parse($day->toDateString().' '.$this->normalizeTime($period->start_time), $tz);
            $end = Carbon::parse($day->toDateString().' '.$this->normalizeTime($period->end_time), $tz);
            for ($cursor = $start->copy(); $cursor->copy()->addMinutes($duration)->lte($end); $cursor->addMinutes($interval)) {
                $slotEnd = $cursor->copy()->addMinutes($duration);
                if ($this->overlapsBreaks($cursor, $slotEnd, $breaks, $day, $tz)) {
                    continue;
                }
                if ($this->overlapsExceptions($cursor, $slotEnd, $exceptions, $tz)) {
                    continue;
                }
                $candidates[] = $cursor->format('H:i');
            }
        }

        $taken = $this->takenSlots($providerId, $day);
        $earliest = Carbon::now($tz)->addMinutes($minNotice);

        $out = [];
        foreach (array_unique($candidates) as $time) {
            $at = Carbon::parse($day->toDateString().' '.$time, $tz);
            $available = ! isset($taken[$time]) && $at->gte($earliest);
            $out[] = [
                'time' => $time,
                'available' => $available,
            ];
        }

        usort($out, fn ($a, $b) => strcmp($a['time'], $b['time']));

        return $out;
    }

    public function assertSlotAvailable(int $providerId, Carbon $date, string $slot, ?ProviderService $service = null): bool
    {
        $slots = $this->slotsFor($providerId, $date, $service);
        foreach ($slots as $row) {
            if ($row['time'] === $slot && $row['available']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string,true>
     */
    public function takenSlots(int $providerId, Carbon $day): array
    {
        $rows = Booking::query()
            ->where('provider_id', $providerId)
            ->whereIn('status', ['PENDING', 'ACCEPTED', 'CONFIRMED'])
            ->where(function ($q) use ($day) {
                $q->whereDate('appointment_date', $day->toDateString())
                    ->orWhereDate('preferred_date', $day->toDateString());
            })
            ->get(['slot', 'preferred_slot', 'appointment_date']);

        $taken = [];
        foreach ($rows as $row) {
            $t = $row->slot ?: $row->preferred_slot;
            if ($t) {
                $taken[substr((string) $t, 0, 5)] = true;
            }
        }

        return $taken;
    }

    /**
     * @return list<array{time:string,available:bool}>
     */
    private function catalogFallback(int $providerId, Carbon $day, int $minNotice, string $tz): array
    {
        $taken = $this->takenSlots($providerId, $day);
        $earliest = Carbon::now($tz)->addMinutes($minNotice);
        $out = [];
        foreach (BookingCatalog::slotTemplate() as $time) {
            $at = Carbon::parse($day->toDateString().' '.$time, $tz);
            $out[] = [
                'time' => $time,
                'available' => ! isset($taken[$time]) && $at->gte($earliest),
            ];
        }

        return $out;
    }

    private function normalizeTime(mixed $time): string
    {
        if ($time instanceof \DateTimeInterface) {
            return Carbon::instance($time)->format('H:i:s');
        }
        $s = (string) $time;
        if (preg_match('/^\d{2}:\d{2}$/', $s)) {
            return $s.':00';
        }

        return $s;
    }

    private function overlapsBreaks(Carbon $start, Carbon $end, Collection $breaks, Carbon $day, string $tz): bool
    {
        foreach ($breaks as $break) {
            $bStart = Carbon::parse($day->toDateString().' '.$this->normalizeTime($break->start_time), $tz);
            $bEnd = Carbon::parse($day->toDateString().' '.$this->normalizeTime($break->end_time), $tz);
            if ($start->lt($bEnd) && $end->gt($bStart)) {
                return true;
            }
        }

        return false;
    }

    private function overlapsExceptions(Carbon $start, Carbon $end, Collection $exceptions, string $tz): bool
    {
        foreach ($exceptions as $ex) {
            if ($ex->all_day) {
                continue; // all-day already emptied the day
            }
            if (! $ex->start_time || ! $ex->end_time) {
                continue;
            }
            $eStart = Carbon::parse($start->toDateString().' '.$this->normalizeTime($ex->start_time), $tz);
            $eEnd = Carbon::parse($start->toDateString().' '.$this->normalizeTime($ex->end_time), $tz);
            if ($start->lt($eEnd) && $end->gt($eStart)) {
                return true;
            }
        }

        return false;
    }
}