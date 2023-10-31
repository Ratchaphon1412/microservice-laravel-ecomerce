<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function color() {
        return $this->belongsTo(Color::class);
    }
    public function stocks() {
        return $this->hasMany(Stock::class);
    }
}
