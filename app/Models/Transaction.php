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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'transaction_id', 'cart_ids', 'description', 'card_number', 'ref_id', 'status', 'amount', 'cart_price', 'tax', 'discount', 'payment_method'];


}
