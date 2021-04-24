<?php

namespace App\Http\Controllers;

use App\CookBookContainer;
use App\MLContainer;
use Illuminate\Http\Request;

// Custom imports
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use phpDocumentor\Reflection\PseudoTypes\False_;

class RecipeController extends Controller {
    // Number of items to show per page
    var $paginate = 7;

    /**
     * Method to show the User's Cookbook Recipes / Guest's Session Recipes
     */
    public function index(CookBookContainer $cookbook) {
        return view('feed', ['recipes' => $cookbook->getRecipes()]);
    }

    /**
     * Method to fetch the next page of paginated data
     */
    public function fetch(Request $request, CookBookContainer $cookbook) {
        // Check that the request is ajax
        if ($request->ajax()) {
            $cookbook->refresh(true);
            return view('components.recipe-panel', ['recipes' => $cookbook->getRecipes()])->render();
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
        // Check if a logged in User needs warning about possible allergens
        if (Auth::check()) {
            // Get Recipe Allergens
            $recipeAllergens = $recipe->ingredients()->with(['allergens'])->get()->map(function ($item) {
                if (count($item->allergens)) return $item->allergens;
            })->flatten()->filter(function ($value) { return !is_null($value); });
            // Get the shared allergens and/or traces
            $userAllergens = Auth::user()->profile->allergens->diff(
                Auth::user()->profile->allergens->diff($recipeAllergens)
            );
        } else {
            $userAllergens = [];
        }

        // dd($recipeAllergens, $userAllergens);

        //Return the recipe with it's included ingredients
        return view('recipe', ['recipe' => $recipe, 'ingredients' => $ingredients, 'hasAllergens' => $userAllergens]);
    }

    /**
     * Method to allow a user to be given a "surprise" recipe - personalised if user is logged in
     */
    public function surprise() {
        // Start the query
        $recipe = Recipe::all();
        // Personalise the query to the User (if one logged in)
        if (Auth::check()) {
            // Check against user allergens (if any)
            if(count(Auth::user()->profile->allergens) > 0) {

            }
            // Check against user ingredients (if more than 3)
            if(count(Auth::user()->fridge->ingredients) > 3) {

            }
        }
        // get a random recipe from the query
        $recipe = $recipe->random(1)->first();
        // Return the recipe
        return $this->show($recipe);
    }

    /**
     * Method to return the page for the AI chef results
     */
    public function showAI(MLContainer $mlContainer) {
        // Get the recipe from the machine learning container
        $recipe = $mlContainer->getRecipe();
        // Return it to the view
        return view('ai-chef', ['recipe' => $recipe, 'user' => Auth::user()]);
    }



    /**
     * Method to create a new Recipe
     */
    public function create() {



    }

}

// TODO Surprise me feature - based on User fridge

// TODO Recommendations based on User preferences

// TODO Warnings if Allergens/Traces contained in recipe

// TODO Warnings if Recipe outside of Users normal taste preferences
