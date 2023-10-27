<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function ImageColor(){
        return $this->hasMany(ImageColor::class);
    }
}
