<?php

namespace App\Support;

use App\Models\Dog;
use App\Models\DogOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DogPurchaseService
{
    /**
     * Statuses that mean the dog is listed for sale.
     *
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

            // One active pending order per dog (lock listing).
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

    public function completePayment(User $buyer, DogOrder $order, string $method = 'wallet'): DogOrder
    {
        return DB::transaction(function () use ($buyer, $order, $method) {
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
                $seller->increment('wallet', $price);
            }

            $ref = 'DOG-'.Str::upper(Str::random(10));

            // transactions.payment_method is enum('online','offline')
            $txMethod = $method === 'wallet' ? 'online' : 'online';

            Transaction::query()->create([
                'user_id' => $buyerLocked->id,
                'transaction_id' => $ref,
                'description' => 'خرید سگ #'.$dog->id.' — '.$dog->title,
                'status' => 'completed',
                'amount' => -$price,
                'cart_price' => $price,
                'payment_method' => $txMethod,
            ]);

            Transaction::query()->create([
                'user_id' => $seller->id,
                'transaction_id' => $ref.'-S',
                'description' => 'فروش سگ #'.$dog->id.' — '.$dog->title,
                'status' => 'completed',
                'amount' => $price,
                'cart_price' => $price,
                'payment_method' => $txMethod,
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

            // Cancel other pending orders for this dog (if any).
            DogOrder::query()
                ->where('dog_id', $dog->id)
                ->where('id', '!=', $order->id)
                ->where('status', DogOrder::STATUS_PENDING)
                ->update(['status' => DogOrder::STATUS_CANCELLED]);

            return $order->fresh(['dog', 'buyer', 'seller']);
        });
    }
}