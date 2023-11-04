<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
}
