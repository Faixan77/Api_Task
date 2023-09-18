<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    protected $fillable = ['name', 'contact_info', 'address'];
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
