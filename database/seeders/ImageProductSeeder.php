<?php

namespace Database\Seeders;

use App\Models\ImageProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image = new ImageProduct();
        $image->product_id = 1;
        $image->image_path = "/788/788/788/788";
        $image->save();
    }
}
