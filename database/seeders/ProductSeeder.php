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
        $product->category_type = "T-shirt";
        $product->gender = "men";
        $product->save();
    }
}
