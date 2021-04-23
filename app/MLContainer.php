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
        $ingredients = Auth::user()->fridge->ingredients->get('name');

        if (!is_null($ingredients) && count($ingredients) > 4) {
            // Run the python script
            $command = escapeshellcmd('python ../resources/scripts/ingredient_pairings.py '.escapeshellarg(json_encode($ingredients)));
            // Get the recipe
            $this->recipe = collect(json_decode(shell_exec($command)));

            // dump the recipe for us to view
            dd($this->recipe);
        }

        // // Get only the names from the collection
        // $ingredients = Ingredient::inRandomOrder()->limit(6)->get(['id','name']);

        // DB::enableQueryLog(); // Enable query log
        // $ingredients = Recipe::with('ingredients:name')->limit(100)->get();//->pluck('ingredients', 'id');

        // dd(DB::getQueryLog()); // Show results of log

        // TODO May require additional formatting here
    }

    /**
     * Method to show the generated recipe
     */
    public function getRecipe() {

        return $this->recipe;
    }

    /**
     * Method to train the ML model - to be called on by a task handler
     */
    public function trainModel() {

    }

}
