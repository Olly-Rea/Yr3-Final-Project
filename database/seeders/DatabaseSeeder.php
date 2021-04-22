<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        echo("\n");

        // Call on Ingredient Seeder
        $this->call(IngredientSeeder::class);

        // Call on User Seeder
        $this->call(UserSeeder::class);
        // Call on Fridge Seeder
        $this->call(FridgeSeeder::class);

        // Call on Characteristic Seeder
        $this->call(CharacteristicSeeder::class);
        // Call on Recipe Seeder
        $this->call(RecipeSeeder::class);
    }

}
