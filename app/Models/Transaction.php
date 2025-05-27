<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property $id
 * @property $user_id
 * @property $transaction_id
 * @property $cart_ids
 * @property $description
 * @property $card_number
 * @property $ref_id
 * @property $status
 * @property $amount
 * @property $cart_price
 * @property $tax
 * @property $discount
 * @property $payment_method
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transaction extends Model
{

    protected $perPage = 20;

    protected $table = 'transactions'; // Change this to your actual table name if it's different


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'transaction_id', 'cart_ids', 'description', 'card_number', 'ref_id', 'status', 'amount', 'cart_price', 'tax', 'discount', 'payment_method'];

    public function user()
    {
        return $this->belongsTo(User::class);
        // اگر کلید خارجی متفاوت است:
        // return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function carts()
{
    return $this->belongsToMany(Cart::class, 'cart_transaction');
}


}
