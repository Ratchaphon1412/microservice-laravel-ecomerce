<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        return Coupon::get();
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:20',
            'type' => 'required|in:Percent,Number',
            'discount' => 'required|numeric|min:1',
            'code' => 'required|string|max:20',
            'expire_date' => 'required|date',
            'limit_coupon' => 'required|numeric|min:1'
        ]);

        $coupon = new Coupon();
        $coupon->name = $request->get('name');
        $coupon->type = $request->get('type');
        $coupon->discount = $request->get('discount');
        $coupon->code = $request->get('code');
        $coupon->expire_date = $request->get('expire_date');
        $coupon->limit_coupon = $request->get('limit_coupon');
        $coupon->save();
        $coupon->refresh();
        
        return $coupon;
    }
    public function show(Coupon $coupon) {
        return  $coupon;
    }
    public function update(Request $request, Coupon $coupon) {
        $request->validate([
            'name' => 'required|string|max:20',
            'type' => 'required|in:Percent,Number',
            'discount' => 'required|numeric|min:1',
            'code' => 'required|string|max:20',
            'expire_date' => 'required|date',
            'limit_coupon' => 'required|numeric|min:1'
        ]);

        $coupon->name = $request->get('name');
        $coupon->type = $request->get('type');
        $coupon->discount = $request->get('discount');
        $coupon->code = $request->get('code');
        $coupon->expire_date = $request->get('expire_date');
        $coupon->limit_coupon = $request->get('limit_coupon');
        $coupon->save();
        $coupon->refresh();
        
        return $coupon;
    }
    public function destroy(Coupon $coupon) {
        $coupon->delete();
        return ['success' => 'Delete this Coupon'];
    }
}
