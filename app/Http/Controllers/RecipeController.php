<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Custom import
use App\Models\Recipe;

class RecipeController extends Controller {
    // Number of items to show per page
    var $paginate = 30;

    // Method to show all recipes (paginated)
    public function index() {
        return view('feed', ['recipes' => Recipe::paginate($this->paginate)]);
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
        return view('recipe', ['recipe' => $recipe]);
    }

}
