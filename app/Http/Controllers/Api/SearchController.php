<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // http://localhost:7700/   <-ดูได้เลยตรงนี้ 
    public function search(Request $request) {
        $orders = Product::search($request)->get();
        return $orders ;
    }
}
