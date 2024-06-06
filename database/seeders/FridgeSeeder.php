<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Fridge, Ingredient};
use Illuminate\Support\Facades\DB;

class FridgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Loop  through all the (currently empty) Fridges
            foreach (Fridge::all() as $fridge) {
                // Get some random ingredients
                $fridgeIngredients = Ingredient::select('id')->get()->random(rand(6, 18))->toArray();
                $ingredientsToAdd = [];
                // Loop through all the selected ingredients
                foreach ($fridgeIngredients as $ingredient) {
                    // generate a random measure-amount pairing
                    $measure = (rand() & 1) ? 'cup' : 'g';
                    $amount = ($measure === 'cup') ? rand(1, 3) : rand(30, 250);
                    // Attach the fridge ingredient
                    $ingredientsToAdd[$ingredient['id']] = ['amount' => $amount, 'measure' => $measure];
                }
                $fridge->ingredients()->syncWithoutDetaching($ingredientsToAdd);
            }
        });
    }
}
