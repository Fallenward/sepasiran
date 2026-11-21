<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingLifecycleService
{
    public function __construct(
        private BookingPaymentService $payments
    ) {}

    /**
     * Cancel a booking (customer/admin). Refunds held escrow once. Idempotent.
     */
    public function cancel(Booking $booking, string $reason = 'cancel', ?string $actorLabel = null): Booking
    {
        return $this->close($booking, 'CANCELLED', $reason, $actorLabel ?? 'لغو رزرو');
    }

    /**
     * Provider rejection. Refunds held escrow once. Idempotent.
     */
    public function reject(Booking $booking, string $reason = 'reject', ?string $rejectionReason = null): Booking
    {
        $label = $rejectionReason
            ? 'رد رزرو: '.$rejectionReason
            : 'رد رزرو توسط ارائه‌دهنده';

        return $this->close($booking, 'REJECTED', $reason, $label);
    }

    /**
     * Atomically close booking to CANCELLED or REJECTED with escrow refund when held.
     */
    public function close(
        Booking $booking,
        string $terminalStatus,
        string $refundReason = 'cancel',
        ?string $notifyTitle = null
    ): Booking {
        if (! in_array($terminalStatus, ['CANCELLED', 'REJECTED'], true)) {
            throw ValidationException::withMessages([
                'status' => 'وضعیت پایانی نامعتبر است.',
            ]);
        }

        return DB::transaction(function () use ($booking, $terminalStatus, $refundReason, $notifyTitle) {
            /** @var Booking $booking */
            $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            $status = strtoupper((string) $booking->status);

            // Idempotent: already in the same terminal state.
            if ($status === $terminalStatus) {
                if ($booking->isHeld()) {
                    $this->payments->refundHeld($booking, $refundReason);
                }

                return $booking->fresh(['provider', 'user', 'dog', 'city']);
            }

            // Already closed the other way — do not double-refund or flip status casually.
            if (in_array($status, ['CANCELLED', 'REJECTED'], true)) {
                return $booking->fresh(['provider', 'user', 'dog', 'city']);
            }

            if (in_array($status, ['COMPLETED'], true)) {
                throw ValidationException::withMessages([
                    'status' => 'رزرو تکمیل‌شده قابل لغو/رد از این مسیر نیست.',
                ]);
            }

            // Settled / confirmed money is not refundHeld territory.
            if ($booking->isSettled() || $status === 'CONFIRMED') {
                throw ValidationException::withMessages([
                    'status' => 'پس از تسویه/تأیید، لغو عادی با بازپرداخت امانی ممکن نیست.',
                ]);
            }

            if (! in_array($status, ['PENDING', 'OPEN', 'ACCEPTED'], true)) {
                throw ValidationException::withMessages([
                    'status' => 'این رزرو قابل لغو/رد نیست.',
                ]);
            }

            if ($booking->isHeld()
                || ((string) $booking->payment_status === 'paid'
                    && (string) $booking->settlement_status === 'held')) {
                $this->payments->refundHeld($booking, $refundReason);
                $booking = $booking->fresh();
            }

            $booking->forceFill(['status' => $terminalStatus])->save();
            $booking = $booking->fresh(['provider', 'user', 'dog', 'city']);

            $this->notifyCustomer($booking, $terminalStatus, $notifyTitle);

            return $booking;
        });
    }

    private function notifyCustomer(Booking $booking, string $terminalStatus, ?string $title): void
    {
        if (! $booking->user_id) {
            return;
        }

        $isReject = $terminalStatus === 'REJECTED';
        $defaultTitle = $isReject ? 'رزرو رد شد' : 'رزرو لغو شد';
        $message = $isReject
            ? 'درخواست رزرو '.$booking->booking_code.' توسط ارائه‌دهنده رد شد.'
            : 'رزرو '.$booking->booking_code.' لغو شد.';

        if ((string) $booking->payment_status === 'refunded'
            || (string) $booking->settlement_status === 'refunded') {
            $message .= ' مبلغ امانی به کیف پول حساب شما در سپاسیران بازگردانده شد (نه بازگشت بانکی).';
        }

        Notification::query()->create([
            'user_id' => $booking->user_id,
            'title' => $title ?: $defaultTitle,
            'message' => $message,
        ]);
    }
}
