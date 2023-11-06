<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = new Color();
        $color->name = "Purple";
        $color->hex_color = "#7752FE";
        $color->save();
        $color = new Color();
        $color->name = "Green";
        $color->hex_color = "#008000";
        $color->save();
        $color = new Color();
        $color->name = "Pink";
        $color->hex_color = "#FFC0CB";
        $color->save();
        $color = new Color();
        $color->name = "Black";
        $color->hex_color = "#000000";
        $color->save();
    }
}
