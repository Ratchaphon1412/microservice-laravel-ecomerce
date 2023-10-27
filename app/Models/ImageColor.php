<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageColor extends Model
{
    use HasFactory;
    public function Color(){
        return $this->belongsTo(Color::class);
    }
}
