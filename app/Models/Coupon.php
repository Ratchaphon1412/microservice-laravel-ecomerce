<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function users():BelongsToMany{
        return $this->belongsToMany(User::class, 'history_coupons','coupon_id','user_id');
    }
}
