<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price'];
    // Define the relationship with variants
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
    use HasFactory;
}
