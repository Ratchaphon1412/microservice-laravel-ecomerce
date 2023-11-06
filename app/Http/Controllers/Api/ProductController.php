<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Stock;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

use function example\read;

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
            'material' => 'required|string|max:255',
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
        $product->price = (int) $request->get('price');
        $product->material = $request->get('material');
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
                $new_stock->quantity = (int) $stock['quantity'];
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
        return response()->json(Product::with('image_products')
         ->with('product_colors.color')
         ->with('product_colors.stocks')
         ->find($product->id), 201);
    }

    public function update(Request $request, Product $product) {
        // return $request->all();
        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string',
            'material' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_type' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'color_list' => 'required|array',
            'color_list.*.name' => 'required|string|max:255',
            'color_list.*.hex_color' => 'required|string|max:7',
            'color_list.*.stock' => 'required|array',
            'color_list.*.stock.*.size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'color_list.*.stock.*.quantity' => 'required|numeric',
            'delete_image_list' => 'array',
            'delete_image_list.*.' => 'string',
            'image_list' => 'array',
            'image_list.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->price = $request->get('price');
        $product->category_type = $request->get('category_type');
        $product->material = $request->get('material');
        $product->gender = $request->get('gender');
        $product->save();
        $product->refresh();

        // updateColor
        $colorInProduct = ProductColor::where('product_id',$product->id)->get();
        $old_color_list = [];



        foreach($colorInProduct as $color){
            $old_color_list[] = Color::where('id',$color->color_id)->first();
        }
        $new_color_list = $request->get('color_list');
        $sort_color_list = [];

        foreach ($old_color_list as $old_color) {
            $colorFound = false;
            foreach ($new_color_list as $new_color) {
                if ($old_color->hex_color == $new_color['hex_color']) {
                    $colorFound = true;
                    break; // No need to continue checking if the color is found
                }
            }
            if (!$colorFound) {
                // The color was not found in $new_color_list, so we can delete it from the database
                $colorInProduct_delete = ProductColor::where('product_id',$product->id)->where('color_id',$old_color->id)->first();
                $colorInProduct_delete->delete();
                $old_color->delete();
            }
            else
            {
                $sort_color_list[] = $old_color;
            }
        }
        // return $sort_color_list;
        foreach ($new_color_list as $new_color) {
            $colorFound = false;
            foreach ($sort_color_list as $existing_color) {
                if ($existing_color['hex_color'] == $new_color['hex_color']) {
                    $colorFound = true;
                    break; // Color already exists in $sort_color_list
                }
            }
            if (!$colorFound) {
                // Add the new color to $sort_color_list
                $newColor = new Color();
                $newColor->name = $new_color['name'];
                $newColor->hex_color = $new_color['hex_color'];
                $newColor->save();
                $newColor->refresh();
                
                $product_color = new ProductColor();
                $product_color->product_id = $product->id;
                $product_color->color_id = $newColor->id;
                $product_color->save();
                $product_color->refresh();

                foreach ($new_color['stock'] as $stock) {
                    if($stock['quantity'] == 0){
                        continue;
                    }
                    $new_stock = new Stock();
                    $new_stock->product_color_id = $product_color->id;
                    $new_stock->size = $stock['size'];
                    $new_stock->quantity = (int) $stock['quantity'];
                    $new_stock->save();
                    $new_stock->refresh();
                }
                $sort_color_list[] = $new_color;
            } else {
                foreach ($new_color['stock'] as $stock) {
                    if($stock['quantity'] == 0){
                        continue;
                    }
                    $temp = ProductColor::byColor($new_color['name'])->where('product_id', $product->id)->first();
                    if ($temp->stocks()->where('size', $stock['size'])->exists()) {
                        $s = $temp->stocks()->where('size', $stock['size'])->first();
                        $s->quantity = (int) $stock['quantity'];
                        $s->save();
                        $s->refresh();
                    } else {
                        $new_stock = new Stock();
                        $new_stock->product_color_id = $temp->id;
                        $new_stock->size = $stock['size'];
                        $new_stock->quantity = $stock['quantity'];
                        $new_stock->save();
                        $new_stock->refresh();
                    }
                }
            }
            return $product;
        }

        // delete image
        $deleteImages = $request->get('delete_image_list');
        if ($deleteImages) {
            foreach ($deleteImages as $image_path) {
                $product_image = ImageProduct::where('product_id', $product->id)->where('image_path', $image_path)->first();
                $product_image->delete();
            }
        }

        // add image
        $image_list = $request->file('image_list');
        if ($image_list) {
            foreach ($image_list as $file) {
                $file->storeAs('products/images',  $file->getClientOriginalName(), 'public');
                $image = new ImageProduct();
                $image->product_id = $product->id;
                $image->image_path = 'http://localhost/storage/products/images/'.$file->getClientOriginalName();
                $image->save();
                $image->refresh();
            }
        }
        return Product::with('image_products')
        ->with('product_colors.color')
        ->with('product_colors.stocks')
        ->find($product->id);
    }

    public function show(Product $product) {
        return Product::with('image_products')
            ->with('product_colors.color')
            ->with('product_colors.stocks')
            ->find($product->id);
    }

    public function destroy(Product $product) {
        $product->delete();
        $product->image_products()->delete();

        $product_colors = ProductColor::where('product_id', $product->id)->get();
        foreach ($product_colors as $pc) {
            $pc->color()->delete();
            $pc->stocks()->delete();
            $pc->delete();
        }

        return ['Success' => 'This product ir removed.'];
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
        $listQty = [];
        $status = "in" ;

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
                    $qty =  intval($stock->quantity);
                    
                    if (!in_array($size, $listSize)) {
                        $listSize[] = $size;
                        $listQty[] = $qty;
                    }
                    if($qty == 0){
                        $status = "out";
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
                    'listQty' => $listQty,
                    'listColor' => $listColor,
                    'listSize' => $listSize,
                    'updateTime' => $product->updated_at,
                    'status' => $status
                ];

                $listColor = [];
                $listSize = [];

                $status = "in" ;
        }
        return $filterList;
    }

    public function defineProduct(Product $product){
        $filterList = [];
        $listColor = [];
        $listImage = [];
        foreach($product->image_products as $image){
            $listImage[] = $image->image_path;
        }

        foreach ($product->product_colors as $product_color) {
            $name_color = $product_color->color->name;
            $hex_color = $product_color->color->hex_color;
            foreach($product_color->stocks as $stock){
                $size = $stock->size;
                $stock = $stock->quantity;
                if (!in_array($name_color, $listColor)) {
                    $listColor[$name_color][] = [
                        'hex_color' => $hex_color,
                        'size' => $size,
                        'stock' => $stock,
                        'inStock' => $stock > 0 ? true : false
                    ];
                }              
            }
        }      
        $filterList = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category_type' => $product->category_type,
                        'description' => $product->description,
                        'material' => $product->material,
                        'price' => $product->price,
                        'gender' => $product->gender,
                        'image' => $listImage,
                        'listColor' => $listColor,
                    ];
    
        return $filterList;
    }
    
    public function defineProductCard(Product $product){
        $filterList = [];
        $listSize = [];
        $listColor = [];

        
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
        $filterList = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'material' => $product->material,
            'price' => $product->price,
            'category' => $product->category_type,
            'gender' => $product->gender,
            'image' => $product->image_products[0]->image_path,
            'listColor' => $listColor,
            'listSize' => $listSize
        ];
        return $filterList;
    }

    public function filter(Request $request){
        // return [$request->selectedSize,$request->selectedColor,$request->cost];
        $checkSize = false;
        $checkColor = false;
        $checkCost = false;
        if($request->selectedSize !== null){
            $checkSize = true;
        }
        if($request->selectedColor !== null){
            $checkColor = true;
        }
        if($request->cost !== 0){
            $checkCost = true;
        }
        // return [$checkSize,$checkColor,$checkCost];
        if($checkSize && $checkColor && $checkCost){
            $products = Product::byColor($request->selectedColor)->bySize($request->selectedSize)->byCost($request->cost)->get();
        }
        else if($checkColor && $checkSize){
            $products = Product::byColor($request->selectedColor)->bySize($request->selectedSize)->get();
        }
        else if($checkColor && $checkCost){
            $products = Product::byColor($request->selectedColor)->byCost($request->cost)->get();
        }
        else if($checkSize && $checkCost){
            $products = Product::bySize($request->selectedSize)->byCost($request->cost)->get();
        }
        else if($checkSize){
            $products = Product::bySize($request->selectedSize)->get();
        }
        else if($checkColor){
            $products = Product::byColor($request->selectedColor)->get();
        }
        else if($checkCost){
            $products = Product::byCost($request->cost)->get();
        }

        $filterList = [];
        foreach($products as $product){
            $filterList[] = $this->defineProductCard($product);
        }
        return $filterList;
    }

    public function searchGenderCategory(Request $request) {
        if ($request->gender === "All") {
            if ($request->category === "All") {
                $products = Product::get();
            } else {
                $products = Product::where("category_type", $request->category)->get();
            }
        } else {
            $product_genders = Product::where('gender', $request->gender);
            if ($request->category === "All") {
                $products = $product_genders->get();
            } else {
                $products = $product_genders->where("category_type", $request->category)->get();
            }
        }

        $filterList = [];
        foreach($products as $product){
            $filterList[] = $this->defineProductCard($product);
        }
        return $filterList;
    }
}
