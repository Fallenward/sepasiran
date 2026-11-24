<?php

namespace App\Support;

use App\Models\Dog;
use App\Models\DogOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class DogPurchaseService
{
    /**
     * @return list<string>
     */
    public static function forSaleStatuses(): array
    {
        return ['available', 'ready', 'for_sale', 'for-sale'];
    }

    public static function isForSale(Dog $dog): bool
    {
        return in_array((string) $dog->status, self::forSaleStatuses(), true)
            && (int) ($dog->price ?? 0) > 0;
    }

    /**
     * @return list<string>
     */
    public static function paymentMethods(): array
    {
        return ['wallet', 'gateway'];
    }

    public function createOrder(User $buyer, Dog $dog): DogOrder
    {
        return DB::transaction(function () use ($buyer, $dog) {
            /** @var Dog $dog */
            $dog = Dog::query()->lockForUpdate()->findOrFail($dog->id);

            if ((int) $dog->user_id === (int) $buyer->id) {
                throw ValidationException::withMessages([
                    'dog' => 'نمی‌توانید سگ خودتان را بخرید.',
                ]);
            }

            if (! self::isForSale($dog)) {
                throw ValidationException::withMessages([
                    'dog' => 'این سگ در حال حاضر برای فروش نیست.',
                ]);
            }

            $existing = DogOrder::query()
                ->where('dog_id', $dog->id)
                ->where('buyer_id', $buyer->id)
                ->where('status', DogOrder::STATUS_PENDING)
                ->first();

            if ($existing) {
                return $existing;
            }

            $open = DogOrder::query()
                ->where('dog_id', $dog->id)
                ->where('status', DogOrder::STATUS_PENDING)
                ->exists();

            if ($open) {
                throw ValidationException::withMessages([
                    'dog' => 'برای این سگ یک سفارش باز وجود دارد.',
                ]);
            }

            $order = DogOrder::query()->create([
                'dog_id' => $dog->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $dog->user_id,
                'price' => (int) $dog->price,
                'status' => DogOrder::STATUS_PENDING,
            ]);

            $dog->update(['status' => 'waiting']);

            return $order->fresh(['dog', 'buyer', 'seller']);
        });
    }

    /**
     * Start bank gateway checkout and return the gateway redirection form/response payload.
     *
     * @return \Shetabit\Multipay\RedirectionForm
     */
    public function startGatewayPayment(User $buyer, DogOrder $order, ?string $driver = null)
    {
        if ((int) $order->buyer_id !== (int) $buyer->id) {
            throw ValidationException::withMessages([
                'order' => 'این سفارش متعلق به شما نیست.',
            ]);
        }

        if (! $order->isPending()) {
            throw ValidationException::withMessages([
                'order' => 'این سفارش قابل پرداخت نیست.',
            ]);
        }

        $driver = $driver ?: (string) config('payment.default', 'local');
        $callback = route('frontend.dog.order.callback', $order->id);

        $invoice = (new Invoice)->amount((int) $order->price);
        $invoice->detail('dog_order_id', $order->id);
        $invoice->detail('dog_id', $order->dog_id);
        $invoice->detail('orderId', $order->id);

        return Payment::via($driver)
            ->callbackUrl($callback)
            ->purchase($invoice, function ($gatewayDriver, $transactionId) use ($order, $driver) {
                $order->update([
                    'payment_method' => 'gateway',
                    'gateway_driver' => $driver,
                    'gateway_transaction_id' => (string) $transactionId,
                    'transaction_ref' => (string) $transactionId,
                ]);
            })
            ->pay();
    }

    /**
     * Verify gateway callback and finalize ownership transfer.
     */
    public function completeGatewayPayment(DogOrder $order, ?string $transactionId = null): DogOrder
    {
        if ($order->isPaid()) {
            return $order->fresh(['dog', 'buyer', 'seller']);
        }

        if (! $order->isPending()) {
            throw ValidationException::withMessages([
                'order' => 'این سفارش قابل پرداخت نیست.',
            ]);
        }

        $txId = $transactionId ?: $order->gateway_transaction_id;
        if (! $txId) {
            throw ValidationException::withMessages([
                'order' => 'شناسه تراکنش درگاه یافت نشد.',
            ]);
        }

        $driver = $order->gateway_driver ?: (string) config('payment.default', 'local');

        try {
            Payment::via($driver)
                ->amount((int) $order->price)
                ->transactionId($txId)
                ->verify();
        } catch (InvalidPaymentException $e) {
            throw ValidationException::withMessages([
                'payment' => $e->getMessage() ?: 'پرداخت درگاه تأیید نشد.',
            ]);
        }

        $buyer = User::query()->findOrFail($order->buyer_id);

        return $this->completePayment($buyer, $order->fresh(), 'gateway', $txId);
    }

    public function completePayment(
        User $buyer,
        DogOrder $order,
        string $method = 'wallet',
        ?string $externalRef = null
    ): DogOrder {
        if (! in_array($method, self::paymentMethods(), true)) {
            throw ValidationException::withMessages([
                'payment_method' => 'روش پرداخت نامعتبر است.',
            ]);
        }

        return DB::transaction(function () use ($buyer, $order, $method, $externalRef) {
            /** @var DogOrder $order */
            $order = DogOrder::query()->lockForUpdate()->findOrFail($order->id);

            if ((int) $order->buyer_id !== (int) $buyer->id) {
                throw ValidationException::withMessages([
                    'order' => 'این سفارش متعلق به شما نیست.',
                ]);
            }

            if ($order->isPaid()) {
                return $order->fresh(['dog', 'buyer', 'seller']);
            }

            if (! $order->isPending()) {
                throw ValidationException::withMessages([
                    'order' => 'این سفارش قابل پرداخت نیست.',
                ]);
            }

            /** @var Dog $dog */
            $dog = Dog::query()->lockForUpdate()->findOrFail($order->dog_id);
            /** @var User $buyerLocked */
            $buyerLocked = User::query()->lockForUpdate()->findOrFail($buyer->id);
            /** @var User $seller */
            $seller = User::query()->lockForUpdate()->findOrFail($order->seller_id);

            if ((int) $dog->user_id !== (int) $order->seller_id) {
                throw ValidationException::withMessages([
                    'dog' => 'مالک سگ تغییر کرده؛ سفارش باطل است.',
                ]);
            }

            $price = (int) $order->price;

            if ($method === 'wallet') {
                if ((int) $buyerLocked->wallet < $price) {
                    throw ValidationException::withMessages([
                        'wallet' => 'موجودی کیف پول کافی نیست.',
                    ]);
                }
                $buyerLocked->decrement('wallet', $price);
            }

            // Seller settlement lands in wallet for both wallet and gateway payments.
            $seller->increment('wallet', $price);

            $ref = $externalRef ?: ('DOG-'.Str::upper(Str::random(10)));

            Transaction::query()->create([
                'user_id' => $buyerLocked->id,
                'transaction_id' => $ref.'-B',
                'description' => ($method === 'gateway' ? 'پرداخت درگاه — ' : 'پرداخت کیف پول — ').'خرید سگ #'.$dog->id.' — '.$dog->title,
                'status' => 'completed',
                'amount' => $method === 'wallet' ? -$price : 0,
                'cart_price' => $price,
                'payment_method' => 'online',
            ]);

            Transaction::query()->create([
                'user_id' => $seller->id,
                'transaction_id' => $ref.'-S',
                'description' => 'فروش سگ #'.$dog->id.' — '.$dog->title,
                'status' => 'completed',
                'amount' => $price,
                'cart_price' => $price,
                'payment_method' => 'online',
            ]);

            $dog->update([
                'user_id' => $buyerLocked->id,
                'status' => 'owned',
            ]);

            $order->update([
                'status' => DogOrder::STATUS_PAID,
                'payment_method' => $method,
                'transaction_ref' => $ref,
                'paid_at' => now(),
            ]);

            DogOrder::query()
                ->where('dog_id', $dog->id)
                ->where('id', '!=', $order->id)
                ->where('status', DogOrder::STATUS_PENDING)
                ->update(['status' => DogOrder::STATUS_CANCELLED]);

            return $order->fresh(['dog', 'buyer', 'seller']);
        });
    }
}