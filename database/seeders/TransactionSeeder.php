<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Cart;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure some cart IDs exist
        $cartIds = Cart::inRandomOrder()->limit(2)->pluck('id')->toArray(); // e.g., [13, 9]

        if (empty($cartIds)) {
            $this->command->warn('No carts found. Please seed carts first.');
            return;
        }

        // Create transaction
        $transaction = Transaction::create([
            'user_id'        => 2, // Ensure this user exists
            'transaction_id' => Str::uuid(),
            'description'    => 'پرداخت سفارش',
            'card_number'    => '610433******1995',
            'ref_id'         => 'RF' . now()->timestamp,
            'status'         => 'pending',
            'amount'         => 347000,
            'base_price'     => 807000,
            'tax'            => 0,
            'discount'       => 0,
            'payment_method' => 'online',
        ]);

        // Attach carts to transaction via pivot table
        // $transaction->carts()->sync($cartIds);

        $this->command->info('Transaction seeded with carts: ' . implode(', ', $cartIds));
    }
}
