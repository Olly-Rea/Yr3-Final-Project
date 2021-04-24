<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\RateLimited;

// Custom import
use Illuminate\Support\Facades\Auth;
use App\Models\{Ingredient, Recipe};
use Illuminate\Support\Facades\DB;

class MLContainer {

    // Variable to hold the recipe
    private $recipe;

    /**
     * Create a new container instance.
     *
     * @return void
     */
    public function __construct() {
        $ingredients = Auth::user()->fridge->ingredients->pluck('name');
        // If the users fridge is not empty AND has 5 or more ingredients
        if (!is_null($ingredients) && count($ingredients) >= 5) {
            // // Run the TF model python script
            // $command = escapeshellcmd('python ../resources/scripts/use_model.py '. escapeshellarg(json_encode($ingredients)));
            // // Get the recipe
            // $this->recipe = json_decode(shell_exec($command));

            // Get the recipe
            $this->recipe = Recipe::inRandomOrder()->first();

            // // dump the recipe for us to view
            // dd($this->recipe);
        }
    }

    /**
     * Method to show the generated recipe
     */
    public function getRecipe() {

        return $this->recipe;

    }

}
