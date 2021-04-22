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
        // $data = Auth::user()->fridge()->get('name');

        // // Get only the names from the collection
        // $ingredients = Ingredient::inRandomOrder()->limit(6)->get(['id','name']);

        DB::enableQueryLog(); // Enable query log
        $ingredients = Recipe::with('ingredients:name')->limit(100)->get();//->pluck('ingredients', 'id');

        dd($ingredients);

        dd(DB::getQueryLog()); // Show results of log

        // Run the python script
        $command = escapeshellcmd('python ../resources/scripts/ingredient_pairings.py '.escapeshellarg(json_encode($ingredients)));
        // Get the recipe
        $this->recipe = collect(json_decode(shell_exec($command)));

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
