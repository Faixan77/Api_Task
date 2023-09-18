<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSale extends Pivot
{
    protected $table = 'product_sale';

    protected $fillable = [
        'quantity',
        'subtotal',
        'discount',
    ];
}
