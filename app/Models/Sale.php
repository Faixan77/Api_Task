<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    protected $fillable = ['customer_id', 'payment_method', 'discount', 'taxes', 'total_amount'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(ProductSale::class)
            ->withPivot('quantity', 'subtotal', 'discount');
    }
}
