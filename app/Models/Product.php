<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

protected $fillable = [
    'title',
    'description',
    'slug',
    'price',
    'discount_price',
    'user_id',
    'stock',
    'is_active',
    'is_featured',
    'sku',
    'barcode',
    'category_id',
    'thumbnail',
    'images',
    'buy_link',
    'created_by',
    'updated_by',
    'brand_id',
    'category_id'
];

// And remove the brand() function from the model


    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function getBrandNameAttribute()
        {
            $brands = [
                1 => 'Brand A',
                2 => 'Brand B',
                3 => 'Brand C',
                // Add more as needed
            ];

            return $brands[$this->brand_id] ?? '—';
        }


}
