<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        $size = ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];

        return [
            'product_color_id' => $this->faker->randomNumber(),
            'size' => $size[$this->faker->numberBetween(0, 7)],
            'quantity' => $this->faker->numberBetween(0, 2000),
        ];
    }
}
