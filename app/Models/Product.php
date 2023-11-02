<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{
    use HasFactory;

    public function product_colors():hasMany{
        return $this->hasMany(ProductColor::class);
    }
    public function image_products() : HasMany
    {
        return $this-> hasMany(ImageProduct ::class);
    }
    protected $fillable = [
        'name',
        'description',
        'color',
        'size',
        'price',
        'category_type',
        'gender'

     ];
}
