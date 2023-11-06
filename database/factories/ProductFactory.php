<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   $category_type = ["Tops", "Outerwear", "Bottoms"];
        $x_category_type = $category_type[array_rand($category_type)];
        $gender = ['Men','Women','Kids','Unisex'];
        return [
            'name' => Str::random(),
            'description' => Str::random(20),
            'material' => Str::random(20),  
            'price'=>rand(100,2000),
            'category_type' => $x_category_type,
            'gender' => $gender[array_rand($gender)],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}