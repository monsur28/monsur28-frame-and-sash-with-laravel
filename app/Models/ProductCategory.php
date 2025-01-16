<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'category_image',
        'accessories_available',
        'accessories_attributes',
        'ingredients_attributes',
        'working_hour_available',
        'wholesale_price_available',
        'market_price_available',
    ];

    protected $casts = [
        'accessories_attributes' => 'array',
        'ingredients_attributes' => 'array',
    ];
}
