<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // echo("\n");
        // // Call on User Seeder
        // $this->call(UserSeeder::class);

        // // Call on Ingredient Seeder
        // $this->call(IngredientSeeder::class);
        // // Count ingredients (Debug)
        // echo("\n" . count(Ingredient::all()) . "\n");

        // Call on Recipe Seeder
        $this->call(RecipeSeeder::class);

        // Count ingredients again (Debug)
        echo("\n" . count(Ingredient::all()) . "\n");
    }
}
