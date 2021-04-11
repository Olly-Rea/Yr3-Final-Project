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
        echo("\n");
        // Call on User Seeder
        $this->call(UserSeeder::class);
        // Call on Ingredient Seeder
        $this->call(IngredientSeeder::class);

        // Call on Recipe Seeder
        $this->call(RecipeSeeder::class);

        // // Count ingredients again (Debug)
        // echo(count(Ingredient::all()) . "\n");
    }
}
