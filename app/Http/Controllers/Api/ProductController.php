<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return Product::get();
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_type' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
        ]);

        $product = new Product();
        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->price = $request->get('price');
        $product->category_type = $request->get('category_type');
        $product->gender = $request->get('gender');
        $product->save();
        $product->refresh();

        return $product;
    }
    public function show(Product $product) {
        return Product::with('product_colors')->find($product->id);
    }
    public function destroy(Product $product) {
        $product->delete();
        return ['success' => 'delete this Product'];
    }

    // Add new Color of Product
    public function addColor(Request $request, Product $product) {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_color' => 'required|string|max:7',
        ]);
        
        $color = new Color();
        $color->name = $request->get('name');
        $color->hex_color = $request->get('hex_color');
        $color->save();
        $color->refresh();

        $product_color = new ProductColor();
        $product_color->product_id = $product->id;
        $product_color->color_id = $color->id;
        $product_color->save();
        $product_color->refresh();

        return Product::with('product_colors')->find($product->id);
    }

    public function getStock(ProductColor $product_color) {
        return Stock::where('product_color_id', $product_color->id)->get();
    }

    // New Stock of Product
    public function storeStock(Request $request, ProductColor $product_color) {
        $request->validate([
            'size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'quantity' => 'required|numeric',
        ]);

        if (Stock::where('product_color_id', $product_color->id)
        ->where('size', $request->get('size'))->exists()) {
            return ['fail' => 'This product already has this size. You must update stock!'];
        }

        $stock = new Stock();
        $stock->product_color_id = $product_color->id;
        $stock->size = $request->get('size');
        $stock->quantity = $request->get('quantity');
        $stock->save();
        $stock->refresh();

        return $stock;
    }

    // Update quantity Stock of Product
    public function addStock(Request $request, ProductColor $product_color) {
        $request->validate([
            'size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'quantity' => 'required|numeric',
        ]);

        if (!Stock::where('product_color_id', $product_color->id)
        ->where('size', $request->get('size'))->exists()) {
            return ['fail' => 'This product has not this size.'];
        }

        $stock = Stock::where('product_color_id', $product_color->id)
            ->where('size', $request->get('size'))->first();

        $stock->quantity += $request->get('quantity');
        $stock->save();
        $stock->refresh();

        return $stock;
    }

    // Reduce quantity Stock of Product
    public function reduceStock(Request $request, ProductColor $product_color) {
        $request->validate([
            'size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'quantity' => 'required|numeric',
        ]);

        if (!Stock::where('product_color_id', $product_color->id)
        ->where('size', $request->get('size'))->exists()) {
            return ['fail' => 'This product has not this size.'];
        }

        $stock = Stock::where('product_color_id', $product_color->id)
            ->where('size', $request->get('size'))->first();

        if ($stock->quantity < $request->get('quantity')) {
            return ['fail' => 'This product has not enough of quantity'];
        }
        
        $stock->quantity -= $request->get('quantity');
        $stock->save();
        $stock->refresh();

        return $stock;
    }
}
