<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return Product::with('image_products')
        ->with('product_colors.color')
        ->with('product_colors.stocks')
        ->get();
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_type' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'color_list' => 'required|array',
            'color_list.*.name' => 'required|string|max:255',
            'color_list.*.hex_color' => 'required|string|max:7',
            'color_list.*.stock' => 'required|array',
            'color_list.*.stock.*.size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'color_list.*.stock.*.quantity' => 'required|numeric',
            'image_list' => 'required|array',
            'image_list.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create Product
        $product = new Product();
        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->price = $request->get('price');
        $product->category_type = $request->get('category_type');
        $product->gender = $request->get('gender');
        $product->save();
        $product->refresh();

        // Create Color
        $color_list = $request->get('color_list');
        foreach ($color_list as $color) {
            $new_color = new Color();
            $new_color->name = $color['name'];
            $new_color->hex_color = $color['hex_color'];
            $new_color->save();
            $new_color->refresh();
            $product_color = new ProductColor();
            $product_color->product_id = $product->id;
            $product_color->color_id = $new_color->id;
            $product_color->save();
            $product_color->refresh();
            // Stock of Product with Color
            foreach ($color['stock'] as $stock) {
                $new_stock = new Stock();
                $new_stock->product_color_id = $product_color->id;
                $new_stock->size = $stock['size'];
                $new_stock->quantity = $stock['quantity'];
                $new_stock->save();
                $new_stock->refresh();
            }
        }
        $image_list = $request->file('image_list');
        foreach ($image_list as $file) {
            $file->storeAs('products/images',  $file->getClientOriginalName(), 'public');
            $image = new ImageProduct();
            $image->product_id = $product->id;
            $image->image_path = 'http://localhost/storage/products/images/'.$file->getClientOriginalName();
            $image->save();
            $image->refresh();
        }
        return Product::with('image_products')
            ->with('product_colors.color')
            ->with('product_colors.stocks')
            ->find($product->id);
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_type' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
        ]);

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
        return Product::with('image_products')
            ->with('product_colors.color')
            ->with('product_colors.stocks')
            ->find($product->id);
    }
    public function destroy(Product $product) {
        $product->delete();
        return ['success' => 'delete this Product'];
    }

    // Add Color
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

    // Get Stock of Product (All size)
    public function getStock(ProductColor $product_color) {
        return ProductColor::with('stocks')
            ->with('product')
            ->with('color')
            ->find($product_color->id);
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
            ->where('size', $request->get('size'))
            ->exists()) {
            $stock = new Stock();
            $stock->product_color_id = $product_color->id;
            $stock->size = $request->get('size');
            $stock->quantity = $request->get('quantity');
            $stock->save();
            $stock->refresh();
        } else {
            $stock = Stock::where('product_color_id', $product_color->id)
            ->where('size', $request->get('size'))->first();
            $stock->quantity += $request->get('quantity');
            $stock->save();
            $stock->refresh();
        }

        return $stock;
    }

    public function format(){
        $products = Product::all();
        $filterList = [];
        $listSize = [];
        $listColor = [];

        foreach ($products as $product) {
            foreach ($product->product_colors as $product_color) {
                $hex_color = $product_color->color->hex_color;
                if (!in_array($hex_color, $listColor)) {
                    $listColor[] = $hex_color;
                }
            }
            foreach ($product->product_colors as $product_color) {
                foreach ($product_color->stocks as $stock) {
                    $size = $stock->size;
                    if (!in_array($size, $listSize)) {
                        $listSize[] = $size;
                    }
                }
            }
                $filterList[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => $product->category_type,
                    'gender' => $product->gender,
                    'image' => $product->image_products[0]->image_path,
                    'listColor' => $listColor,
                    'listSize' => $listSize
                ];
                $listColor = [];
                $listSize = [];
        }
        return $filterList;
    }

    public function filter(Request $request){
        return $request;
        $products = Product::all();
        $filterList = [];
        $listSize = [];
        $listColor = [];

        foreach ($products as $product) {
            foreach ($product->product_colors as $product_color) {
                $hex_color = $product_color->color->hex_color;
                if (!in_array($hex_color, $listColor)) {
                    $listColor[] = $hex_color;
                }
            }
            foreach ($product->product_colors as $product_color) {
                foreach ($product_color->stocks as $stock) {
                    $size = $stock->size;
                    if (!in_array($size, $listSize)) {
                        $listSize[] = $size;
                    }
                }
            }
                $filterList[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => $product->category_type,
                    'gender' => $product->gender,
                    'image' => $product->image_products[0]->image_path,
                    'listColor' => $listColor,
                    'listSize' => $listSize
                ];
                $listColor = [];
                $listSize = [];
        }
        return $filterList;
    }
}
