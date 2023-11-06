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
        $type =['Percent', 'Number'];

        for ($i=0; $i < 30 ; $i++) { 
            $count = rand(10,40);
            $type_cou = $type[array_rand($type)];
            if ($type_cou == 'Percent') {
                $coupon = new Coupon();
                $coupon->name = "discount_"."$count"."%";
                $coupon->code = fake()->unique()->word(10);
                $coupon->type = $type_cou;
                $coupon->discount = $count;
                $coupon->limit_coupon = rand(100,200);
                $coupon->expire_date = fake()->dateTimeBetween('-60 days','+60 days');
                $coupon->save();
            }
            else{
                $coupon = new Coupon();
                $coupon->name = "discount_"."$count"."Bath";
                $coupon->type = $type_cou;
                $coupon->code = fake()->unique()->word(10);
                $coupon->discount = $count;
                $coupon->limit_coupon = rand(100,200);
                $coupon->expire_date = fake()->dateTimeBetween('-60 days','+60 days');
                $coupon->save();
            }
        }

    }
}
