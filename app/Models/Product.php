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

    public function searchableAs(): string
    {
        return 'products_index';
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

    public function scopeByColor($query, $color) {
        return $query->whereHas('product_colors.color', function ($query) use ($color) {
            $query->where('name', $color);
        });
    }

    public function scopeBySize($query, $size) {
        return $query->whereHas('product_colors.stocks', function ($query) use ($size) {
            $query->where('size', $size)->where('quantity', '>', 0);
        });
    }

    public function scopeByCost($query, $cost) {
        return $query->where('price', $cost);
    }
     
    public function scopeByCategory($query, $category) {
        return $query->where('category_type', $category);
    }
}
