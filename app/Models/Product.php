<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property $id
 * @property $title
 * @property $description
 * @property $slug
 * @property $price
 * @property $discount_price
 * @property $stock
 * @property $is_active
 * @property $is_featured
 * @property $sku
 * @property $barcode
 * @property $category_id
 * @property $brand_id
 * @property $thumbnail
 * @property $images
 * @property $buy_link
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'description', 'slug', 'price', 'discount_price', 'stock', 'is_active', 'is_featured', 'sku', 'barcode', 'category_id', 'brand_id', 'thumbnail', 'images', 'buy_link', 'created_by', 'updated_by'];


}
