<?php

namespace App\Support;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardFinance
{
    /**
     * Last N months of completed transaction totals for a user.
     *
     * @return array{labels: list<string>, inflow: list<float|int>, outflow: list<float|int>, net: list<float|int>}
     */
    public static function monthlySeries(User $user, int $months = 6): array
    {
        $months = max(3, min(12, $months));
        $cursor = now()->startOfMonth()->subMonths($months - 1);

        $labels = [];
        $inflow = [];
        $outflow = [];
        $net = [];

        for ($i = 0; $i < $months; $i++) {
            $start = $cursor->copy()->addMonths($i)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $rows = Transaction::query()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->get(['amount']);

            $in = (float) $rows->filter(fn ($t) => (float) $t->amount >= 0)->sum('amount');
            $out = (float) abs($rows->filter(fn ($t) => (float) $t->amount < 0)->sum('amount'));

            $labels[] = self::monthLabel($start);
            $inflow[] = round($in);
            $outflow[] = round($out);
            $net[] = round($in - $out);
        }

        return compact('labels', 'inflow', 'outflow', 'net');
    }

    private static function monthLabel(Carbon $month): string
    {
        if (class_exists(\Morilog\Jalali\Jalalian::class)) {
            return \Morilog\Jalali\Jalalian::fromCarbon($month)->format('Y/m');
        }

        return $month->format('Y-m');
    }
}
