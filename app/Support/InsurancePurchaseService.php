<?php

namespace App\Support;

use App\Models\Dog;
use App\Models\InsuranceLicense;
use App\Models\InsurancePlan;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InsurancePurchaseService
{
    public function purchase(User $customer, InsurancePlan $plan, ?int $dogId = null, string $paymentMethod = 'wallet'): InsuranceLicense
    {
        if (! $plan->is_active) {
            throw ValidationException::withMessages([
                'plan' => 'این طرح بیمه فعال نیست.',
            ]);
        }

        if (! in_array($paymentMethod, ['wallet'], true)) {
            throw ValidationException::withMessages([
                'payment_method' => 'روش پرداخت پشتیبانی نمی‌شود.',
            ]);
        }

        $dog = null;
        if ($dogId) {
            $dog = Dog::query()->whereKey($dogId)->where('user_id', $customer->id)->first();
            if (! $dog) {
                throw ValidationException::withMessages([
                    'dog_id' => 'سگ انتخاب‌شده متعلق به شما نیست.',
                ]);
            }
        }

        $amount = (int) $plan->price;
        if ($amount < 1) {
            throw ValidationException::withMessages([
                'plan' => 'مبلغ طرح بیمه نامعتبر است.',
            ]);
        }

        $tz = config('app.timezone', 'Asia/Tehran');

        return DB::transaction(function () use ($customer, $plan, $dog, $amount, $paymentMethod, $tz) {
            /** @var User $locked */
            $locked = User::query()->lockForUpdate()->findOrFail($customer->id);

            if ((int) $locked->wallet < $amount) {
                throw ValidationException::withMessages([
                    'wallet' => 'موجودی کیف پول کافی نیست.',
                ]);
            }

            $locked->decrement('wallet', $amount);

            $starts = Carbon::now($tz);
            $ends = $starts->copy()->addMonthsNoOverflow((int) $plan->duration_months)->endOfDay();
            $code = $this->uniqueLicenseCode();

            $tx = Transaction::query()->create([
                'user_id' => $locked->id,
                'transaction_id' => 'INS-'.$code,
                'description' => 'خرید بیمه '.$plan->insurance?->name.' — طرح '.$plan->name.' ('.$plan->duration_months.' ماه)',
                'status' => 'completed',
                'amount' => -$amount,
                'cart_price' => $amount,
                'payment_method' => 'online',
            ]);

            $license = InsuranceLicense::query()->create([
                'license_code' => $code,
                'user_id' => $locked->id,
                'insurance_id' => $plan->insurance_id,
                'insurance_plan_id' => $plan->id,
                'dog_id' => $dog?->id,
                'starts_at' => $starts,
                'ends_at' => $ends,
                'status' => 'active',
                'amount_paid' => $amount,
                'payment_method' => $paymentMethod,
                'transaction_id' => $tx->id,
            ]);

            if ($dog && Schema::hasColumn('dogs', 'insurance_id')) {
                $dog->forceFill(['insurance_id' => $plan->insurance_id])->save();
            }

            Notification::query()->create([
                'user_id' => $locked->id,
                'title' => 'مجوز بیمه صادر شد',
                'message' => 'مجوز '.$code.' برای طرح '.$plan->name.' تا '
                    .$ends->format('Y/m/d').' در داشبورد شما فعال است.',
            ]);

            return $license->fresh(['insurance', 'plan', 'dog']);
        });
    }

    private function uniqueLicenseCode(): string
    {
        do {
            $code = 'INS-'.Str::upper(Str::random(8));
        } while (InsuranceLicense::query()->where('license_code', $code)->exists());

        return $code;
    }
}
