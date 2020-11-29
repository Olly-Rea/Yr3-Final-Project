<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

// Custom import
use Illuminate\Support\Facades\File;

class IngredientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $json = File::get('database/data/ingredients.json');
        $ingredients = json_decode($json);

        // dd($ingredients);

        // Seed Ingredient Database
        foreach($ingredients as $ingredient) {
            Ingredient::create([
                'name' => ucwords($ingredient->name),
                'url' => $ingredient->url,
                'products' => $ingredient->products,
            ]);
        }
        // Create placeholder ingredient
        Ingredient::create([
            'name' => 'placeholder',
            'url' => null,
            'products' => 0,
        ]);

        // dd($ingredients);

        // \App\Models\Ingredient::factory(100)->create();
    }
}
