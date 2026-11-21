<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class BookingPaymentService
{
    /**
     * @return list<string>
     */
    public static function paymentMethods(): array
    {
        return ['wallet', 'gateway', 'offline'];
    }

    public function needsOnlinePayment(Booking $booking): bool
    {
        if ((string) $booking->payment_status === 'paid') {
            return false;
        }

        if ((string) $booking->payment_method === 'offline') {
            return false;
        }

        return (int) round((float) $booking->total) > 0;
    }

    public function startGatewayPayment(User $customer, Booking $booking, ?string $driver = null)
    {
        $this->assertPayable($customer, $booking);

        $driver = $driver ?: (string) config('payment.default', 'local');
        $callback = route('frontend.booking.pay.callback', $booking->booking_code);
        $amount = (int) round((float) $booking->total);

        $invoice = (new Invoice)->amount($amount);
        $invoice->detail('booking_code', $booking->booking_code);
        $invoice->detail('booking_id', $booking->id);
        $invoice->detail('orderId', $booking->id);

        return Payment::via($driver)
            ->callbackUrl($callback)
            ->purchase($invoice, function ($gatewayDriver, $transactionId) use ($booking, $driver) {
                $booking->update([
                    'payment_method' => 'online',
                    'gateway_driver' => $driver,
                    'gateway_transaction_id' => (string) $transactionId,
                ]);
            })
            ->pay();
    }

    public function completeGatewayPayment(Booking $booking, ?string $transactionId = null): Booking
    {
        if ((string) $booking->payment_status === 'paid') {
            return $booking->fresh(['provider', 'user', 'dog', 'city']);
        }

        $txId = $transactionId ?: $booking->gateway_transaction_id;
        if (! $txId) {
            throw ValidationException::withMessages([
                'payment' => 'شناسه تراکنش درگاه یافت نشد.',
            ]);
        }

        $driver = $booking->gateway_driver ?: (string) config('payment.default', 'local');
        $amount = (int) round((float) $booking->total);

        try {
            Payment::via($driver)
                ->amount($amount)
                ->transactionId($txId)
                ->verify();
        } catch (InvalidPaymentException $e) {
            throw ValidationException::withMessages([
                'payment' => $e->getMessage() ?: 'پرداخت درگاه تأیید نشد.',
            ]);
        }

        $customer = User::query()->findOrFail($booking->user_id);

        return $this->completePayment($customer, $booking->fresh(), 'gateway', $txId);
    }

    public function completePayment(
        User $customer,
        Booking $booking,
        string $method = 'wallet',
        ?string $externalRef = null
    ): Booking {
        if (! in_array($method, ['wallet', 'gateway'], true)) {
            throw ValidationException::withMessages([
                'payment_method' => 'روش پرداخت نامعتبر است.',
            ]);
        }

        return DB::transaction(function () use ($customer, $booking, $method, $externalRef) {
            /** @var Booking $booking */
            $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            $this->assertPayable($customer, $booking, false);

            if ((string) $booking->payment_status === 'paid') {
                return $booking->fresh(['provider', 'user', 'dog', 'city']);
            }

            /** @var User $customerLocked */
            $customerLocked = User::query()->lockForUpdate()->findOrFail($customer->id);
            $amount = (int) round((float) $booking->total);

            if ($method === 'wallet') {
                if ((int) $customerLocked->wallet < $amount) {
                    throw ValidationException::withMessages([
                        'wallet' => 'موجودی کیف پول کافی نیست.',
                    ]);
                }
                $customerLocked->decrement('wallet', $amount);
            }

            $provider = null;
            if ($booking->provider_id) {
                $provider = User::query()->lockForUpdate()->find($booking->provider_id);
                if ($provider) {
                    $provider->increment('wallet', $amount);
                }
            }

            $ref = $externalRef ?: ('BK-'.Str::upper(Str::random(10)));

            $customerTx = Transaction::query()->create([
                'user_id' => $customerLocked->id,
                'transaction_id' => $ref.'-C',
                'description' => ($method === 'gateway' ? 'پرداخت درگاه — ' : 'پرداخت کیف پول — ').'رزرو '.$booking->booking_code,
                'status' => 'completed',
                'amount' => $method === 'wallet' ? -$amount : 0,
                'cart_price' => $amount,
                'payment_method' => 'online',
            ]);

            if ($provider) {
                Transaction::query()->create([
                    'user_id' => $provider->id,
                    'transaction_id' => $ref.'-P',
                    'description' => 'دریافت رزرو '.$booking->booking_code,
                    'status' => 'completed',
                    'amount' => $amount,
                    'cart_price' => $amount,
                    'payment_method' => 'online',
                ]);
            }

            $booking->update([
                // Enum column only allows online|offline; channel detail is in gateway_driver.
                'payment_method' => 'online',
                'gateway_driver' => $method === 'wallet'
                    ? 'wallet'
                    : ($booking->gateway_driver ?: (string) config('payment.default', 'local')),
                'gateway_transaction_id' => $method === 'gateway'
                    ? ($externalRef ?: $booking->gateway_transaction_id)
                    : $booking->gateway_transaction_id,
                'payment_status' => 'paid',
                'transaction_id' => $customerTx->id,
                'status' => in_array((string) $booking->status, ['PENDING', 'ACCEPTED'], true)
                    ? 'CONFIRMED'
                    : $booking->status,
            ]);

            return $booking->fresh(['provider', 'user', 'dog', 'city']);
        });
    }

    private function assertPayable(User $customer, Booking $booking, bool $requirePendingPay = true): void
    {
        if ((int) $booking->user_id !== (int) $customer->id) {
            throw ValidationException::withMessages([
                'booking' => 'این رزرو متعلق به شما نیست.',
            ]);
        }

        if ($requirePendingPay && (string) $booking->payment_status === 'paid') {
            throw ValidationException::withMessages([
                'booking' => 'این رزرو قبلاً پرداخت شده است.',
            ]);
        }

        if (in_array((string) $booking->status, ['CANCELLED'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'رزرو لغو شده قابل پرداخت نیست.',
            ]);
        }

        if ((int) round((float) $booking->total) < 1) {
            throw ValidationException::withMessages([
                'booking' => 'مبلغ رزرو برای پرداخت معتبر نیست.',
            ]);
        }
    }
}
