<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Custom imports
use App\Models\{User, Ingredient};


class FridgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        // Loop  through all the Seeded Users
        foreach (User::all() as $user) {

            // Get some random ingredients
            $fridgeIngredients = Ingredient::all()->random(rand(6, 18));

            // Loop through all the selected ingredients
            foreach($fridgeIngredients as $ingredient) {
                // generate a random measure-amount pairing
                $measure = rand(0,1) ? 'cup' : 'g';
                $amount = ($measure == 'cup') ? rand(1, 3) : rand(30, 250);
                // Attach the fridge ingredient
                $user->fridge->ingredients()->syncWithoutDetaching([
                    $ingredient->id => ['amount' => $amount, 'measure' => $measure]
                ]);
            }
        }

    }
}
