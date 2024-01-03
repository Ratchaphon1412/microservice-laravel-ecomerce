<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Color;
use App\Models\ImageProduct;
use App\Models\ProductColor;
use App\Models\Stock;
use Database\Factories\ColorFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {$image = UploadedFile::fake()->image('test_image.jpg',100);
        $productData = [
            'name' => 'Test Product',
            'description' => 'Product Description',
            'price' => 100.00,
            'category_type' => 'T-shirt',
            'gender' => 'Men',
            'material' => 'Cotton',
            'color_list' => [
                [
                    'name' => 'Red',
                    'hex_color' => '#FF0000',
                    'stock' => [
                        ['size' => 'M', 'quantity' => 50],
                        ['size' => 'L', 'quantity' => 75],
                    ],
                ],
            ],
            'image_list' => [$image],
        ];

        

        $response = $this->json('POST', '/api/product', $productData);
        $response->assertStatus(201); // Check for successful creation
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertDatabaseHas('colors', ['name' =>'Red'] );
        $this->assertDatabaseHas('product_colors', ['product_id' => 1, 'color_id' => 1]);
        $this->assertDatabaseHas('stocks', ['product_color_id' => 1, 'size' => 'M', 'quantity' => 50]);
        $this->assertDatabaseHas('stocks', ['product_color_id' => 1, 'size' => 'L', 'quantity' => 75]);
        $this->assertDatabaseHas('image_products', ['product_id' => 1]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $color = Color::factory()->create();
        $product_color = ProductColor::factory()->create(['product_id' => $product->id , 'color_id' => $color->id]);
        $stocks = Stock::factory()->create(['product_color_id' => $product_color->id]);
        $image = UploadedFile::fake()->image('test_image.jpg',100);
        $updatedProductData = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 200.00,
            'category_type' => 'Shirt',
            'gender' => 'Women',
            'material' => 'Silk',
            'color_list' => [
                [
                    'name' => 'Blue',
                    'hex_color' => '#0000FF',
                    'stock' => [
                        ['size' => 'M', 'quantity' => 10],
                        ['size' => 'L', 'quantity' => 20],
                    ],
                ],
            ],
            'image_list' => [$image],
        ];

        $response = $this->json('PUT', "/api/product/{$product->id}", $updatedProductData);

        $response->assertStatus(200); // Check for successful update
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
        $this->assertDatabaseHas('colors', ['name' => 'Blue']);
        $this->assertDatabaseHas('product_colors', ['product_id' => $product->id, 'color_id' => $color->id + 1]);  //เพิ่มสีเข้าไปใหม่
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->json('DELETE', "/api/product/{$product->id}");

        $response->assertStatus(200); // Check for successful deletion
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('colors', ['id' => 1]); // Assuming color is deleted along with product
    }
}
