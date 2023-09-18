<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'category_id', 'quantity'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)
            ->using(ProductSale::class)
            ->withPivot('quantity', 'subtotal', 'discount');
    }
}
