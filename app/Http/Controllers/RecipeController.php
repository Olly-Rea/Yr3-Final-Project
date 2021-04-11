<?php

namespace App\Http\Controllers;

use App\MLContainer;
use Illuminate\Http\Request;

// Custom imports
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;

class RecipeController extends Controller {
    // Number of items to show per page
    var $paginate = 7;

    // Method to show 7 random recipes in the user feed
    public function index() {
        return view('feed', ['recipes' => Recipe::all()->random(7)]);
    }

    /**
     * Method to fetch the next page of paginated data
     */
    public function fetch(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Get the next page of paginated recipes
            $recipes = Recipe::paginate($this->paginate);
            // If $recipes != null and is > 0...
            if($recipes != null && count($recipes) > 0) {
                // ...render the recipes and return them to the feed
                return view('components.recipe-panel', ['recipes' => $recipes])->render();
            } else {
                return null;
            }
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to display a single recipe page
     */
    public function show(Recipe $recipe) {
        // Eager load the Recipe's Ingredient Alternatives - specific to this recipe
        $ingredients = $recipe->ingredients()->with(['alternatives' => function ($query) use ($recipe) {
            $query->where('recipe_id', '=', $recipe->id);
        }])->get();
        //Return the recipe with it's included ingredients
        return view('recipe', ['recipe' => $recipe, 'ingredients' => $ingredients]);
    }

    /**
     * Method to allow a user to be given a "surprise" recipe - personalised if user is logged in
     */
    public function surprise() {
        // Check to see if a User is logged in
        if(count(Auth::user()->fridge->ingredients) == 0) {
            return $this->show(Recipe::all()->random(1)->first());
        } else {
            return $this->show(Recipe::all()->random(1)->first());
        }
    }

    /**
     * Method to return the page for the AI chef results
     */
    public function showAI(MLContainer $mlContainer) {
        // Get the recipe from the machine learning container
        $recipe = $mlContainer->getRecipe();

        dd($recipe);

        // Return it to the view
        return view('ai-chef', ['recipe' => $recipe]);
    }

}
