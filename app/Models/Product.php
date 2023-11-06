<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;


class Product extends Model
{
    use Searchable;
    use HasFactory;

    public function product_colors() : HasMany{
        return $this->hasMany(ProductColor::class);
    }
    public function image_products() : HasMany
    {
        return $this->hasMany(ImageProduct::class);
    }
    

    public function searchableAs(): string
    {
        return 'products_index';
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - H:i:s'); // Change the format as needed
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - H:i:s'); // Change the format as needed
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
        if ($cost === 500) {
            return $query->where('price', '<', $cost);
        } else if ($cost === 999) {
            return $query->where('price', '>=', 500)->where('price', '<=', $cost);
        } else if ($cost === 1000) {
            return $query->where('price', '>=', $cost);
        } else {
            return false;
        }
    }
     
    public function scopeByCategory($query, $category) {
        return $query->where('category_type', $category);
    }
    
}
