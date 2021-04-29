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
        // Get the ingredient names to use for NLG model
        $ingredients = Auth::user()->fridge->ingredients->pluck('name');

        // If the users fridge is not empty AND has 5 or more ingredients
        if (!is_null($ingredients) && count($ingredients) >= 5) {

            // // Run the TF model python script
            // $command = escapeshellcmd('python ../resources/scripts/use_model.py ' . escapeshellarg(json_encode($ingredients)));
            // // Get the recipe
            // $directions = json_decode(shell_exec($command));

            // Return the demo ingredients and generated text
            $ingredients = ["Milk", "Eggs", "Flour", "Sugar", "Cocoa"];
            $instructions = [
                "Coconut with hot almond coconut milk butter a large eyed peas",
                "In hard over hard over hard in hard with hard over hard in hard eggs and caramelized the oven rack and stir milk and line a large skillet",
                "With flour a large skillet lentils and heat oil and",
                "Confectioners' powdered confectioners' powdered confectioners' powdered confectioners' confectioner's confectioners' sugar and place bacon and mix",
                "With over with cocoa powder 2 cups mix 1 hour preheat oven rack in a large"
            ];

            // Create a collection for the recipe and add the data to it
            $this->recipe = collect();
            $this->recipe->ingredients = collect();
            foreach($ingredients as $ingredient) {
                $toAdd = collect();
                $toAdd->name = $ingredient;
                $toAdd->alternatives = collect();
                $this->recipe->ingredients->push($toAdd);
            }
            $this->recipe->instructions = collect();
            foreach($instructions as $instruction) {
                $toAdd = collect();
                $toAdd->content = $instruction;
                $this->recipe->instructions->push($toAdd);
            }
        }
    }

    /**
     * Method to show the generated recipe
     */
    public function getRecipe() {

        return $this->recipe;

    }

}
