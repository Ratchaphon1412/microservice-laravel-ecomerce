<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = "TOTO";
        $product->description = "10 20 30 40";
        $product->price = 69.69;
        $product->category_type = "TOPS";
        $product->gender = "men";
        $product->save();

        for ($i=0; $i <10 ; $i++) { 
            $product = new Product();
            $product->name = "เสื้อ"+"$i" ;
            $product->description = "เสื้อออเสื้อออเสื้อออ";
            $product->material = "ผ้าฝ้ายผ้าไหม";
            $product->price = 123 + $i;
            $product->category_type = "TOPS";
            $product->gender = "Men";
            $product->save();
        }
        for ($i=0; $i <10 ; $i++) { 
            $product = new Product();
            $product->name = "กางเกง"+"$i" ;
            $product->description = "กางเกงกางเกงกางเกง";
            $product->material = "หนังจระเข้";
            $product->price = 123 + $i;
            $product->category_type = "Outerwear";
            $product->gender = "Men";
            $product->save();
        }
    }
}
