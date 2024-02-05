<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{

    protected $fillable = ['name', 'sku', 'additional_cost', 'stock_count'];

    // Define the inverse relationship with products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    use HasFactory;
}
