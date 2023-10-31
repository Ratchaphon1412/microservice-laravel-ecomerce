<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{
    use HasFactory;

    public function colors():BelongsToMany{
        return $this->belongsToMany(Color::class, 'product_color','product_id','color_id');
    }

    public function images() : HasMany
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
