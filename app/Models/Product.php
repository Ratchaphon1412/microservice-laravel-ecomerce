<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function color()
    {
        return $this->hasMany(Color::class);
    }
    public function size()
    {
        return $this->hasMany(Size::class);
    }
}
