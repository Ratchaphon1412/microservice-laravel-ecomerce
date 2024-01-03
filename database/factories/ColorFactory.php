<?php

namespace Database\Factories;

use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColorFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'name' => "Purple",
            'hex_color' => "#7752FE",
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}