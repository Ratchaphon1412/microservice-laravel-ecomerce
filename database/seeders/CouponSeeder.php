<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupon = new Coupon();
        $coupon->name = "discount_15%";
        $coupon->type = "%";
        $coupon->discount = 15;
        $coupon->save();
    }
}
