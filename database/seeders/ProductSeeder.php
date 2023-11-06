<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductColor;
use App\Models\Stock;
use App\Models\ImageProduct;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_type = ["Tops, Outerwear, Bottoms"];
        $gender = ['Men','Women','Kids','Unisex'];
        $size =['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];


        for ($i=0; $i <150 ; $i++) { 
            $x_category_type = $category_type[array_rand($category_type)];
            $product = new Product();
            $product->name = "$x_category_type"."$i" ;
            $product->description = fake()->sentence;
            $product->material = fake()->sentence;
            $product->price = rand(100,2000);
            $product->category_type = $x_category_type;
            $product->gender = $gender[array_rand($gender)];
            $product->save();


            $image = new ImageProduct();
            $image->product_id = $product->id;
            $image->image_path = 'http://localhost/storage/products/images/123123.jpeg';
            $image->save();
            $image = new ImageProduct();
            $image->product_id = $product->id;
            $image->image_path = 'http://localhost/storage/products/images/123123123.jpeg';
            $image->save();
            $image = new ImageProduct();
            $image->product_id = $product->id;
            $image->image_path = 'http://localhost/storage/products/images/FB_IMG_1695664557388.jpeg';
            $image->save();
            
            $count = rand(1,4);

            for ($j=0; $j < $count ; $j++) { 
                $productColor = new ProductColor();
                $productColor->product_id = $product->id;
                $productColor->color_id = $count;
                $productColor->save();
                $count1 = rand(1,8);
                for ($k=0; $k <$count1  ; $k++) { 
                    $stock= new Stock();
                    $stock->product_color_id = $productColor->id;
                    $stock->size = $size[array_rand($size)];
                    $stock->quantity = rand(0,2000);
                    $stock->save();
                }
            }
            
        }
        
    }
}
