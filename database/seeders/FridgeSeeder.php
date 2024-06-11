<?php

namespace Database\Seeders;

use App\Models\Fridge;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FridgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            // Loop  through all the (currently empty) Fridges
            foreach (Fridge::all() as $fridge) {
                // Get some random ingredients
                $fridgeIngredients = Ingredient::select('id')->get()->random(random_int(6, 18))->toArray();
                $ingredientsToAdd = [];
                // Loop through all the selected ingredients
                foreach ($fridgeIngredients as $ingredient) {
                    // generate a random measure-amount pairing
                    $measure = (random_int(0, getrandmax()) & 1) ? 'cup' : 'g';
                    $amount = ($measure === 'cup') ? random_int(1, 3) : random_int(30, 250);
                    // Attach the fridge ingredient
                    $ingredientsToAdd[$ingredient['id']] = ['amount' => $amount, 'measure' => $measure];
                }
                $fridge->ingredients()->syncWithoutDetaching($ingredientsToAdd);
            }
        });
    }
}
