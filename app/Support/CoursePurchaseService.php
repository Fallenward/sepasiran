<?php

namespace App\Support;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CoursePurchaseService
{
    public function purchase(User $customer, Course $course, string $paymentMethod = 'wallet'): CourseEnrollment
    {
        if (! in_array($paymentMethod, ['wallet'], true)) {
            throw ValidationException::withMessages([
                'payment_method' => 'روش پرداخت پشتیبانی نمی‌شود.',
            ]);
        }

        $existing = CourseEnrollment::query()
            ->where('user_id', $customer->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();
        if ($existing) {
            throw ValidationException::withMessages([
                'course' => 'شما قبلاً این دوره را خریده‌اید.',
            ]);
        }

        $amount = (int) (($course->discount_price && $course->discount_price > 0 && $course->discount_price < $course->price)
            ? $course->discount_price
            : ($course->price ?? 0));

        return DB::transaction(function () use ($customer, $course, $amount, $paymentMethod) {
            /** @var User $locked */
            $locked = User::query()->lockForUpdate()->findOrFail($customer->id);

            if ($amount > 0 && (int) $locked->wallet < $amount) {
                throw ValidationException::withMessages([
                    'wallet' => 'موجودی کیف پول کافی نیست.',
                ]);
            }

            if ($amount > 0) {
                $locked->decrement('wallet', $amount);
            }

            $code = $this->uniqueCode();
            $tx = null;
            if ($amount > 0) {
                $tx = Transaction::query()->create([
                    'user_id' => $locked->id,
                    'transaction_id' => 'CR-'.$code,
                    'description' => 'خرید دوره '.$course->title,
                    'status' => 'completed',
                    'amount' => -$amount,
                    'cart_price' => $amount,
                    'payment_method' => 'online',
                ]);
            }

            $enrollment = CourseEnrollment::query()->create([
                'user_id' => $locked->id,
                'course_id' => $course->id,
                'enrollment_code' => $code,
                'status' => 'active',
                'amount_paid' => $amount,
                'payment_method' => $amount > 0 ? $paymentMethod : 'free',
                'transaction_id' => $tx?->id,
                'purchased_at' => now(config('app.timezone', 'Asia/Tehran')),
            ]);

            Notification::query()->create([
                'user_id' => $locked->id,
                'title' => 'ثبت‌نام در دوره',
                'message' => 'دسترسی به دوره «'.$course->title.'» با کد '.$code.' فعال شد.',
            ]);

            return $enrollment->fresh(['course']);
        });
    }

    private function uniqueCode(): string
    {
        do {
            $code = 'CR-'.Str::upper(Str::random(8));
        } while (CourseEnrollment::query()->where('enrollment_code', $code)->exists());

        return $code;
    }
}