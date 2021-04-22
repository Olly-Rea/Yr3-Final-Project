<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use App\Models\{Recipe, Ingredient};
use Illuminate\Support\Facades\Auth;

class FridgeController extends Controller {

    /**
     * Method to retrieve a recipe based on the ingredients in a Users fridge
     */
    public function recipeFrom() {

        // Get Recipes that contain a subset of the Users Fridge Ingredients
        $allRecipes = Recipe::with('ingredients:name')->where('');

        // Create the recipes collection
        $recipes = collect();

        // $recipe->ingredients()->with(['alternatives' => function ($query) use ($recipe) {
        //     $query->where('recipe_id', '=', $recipe->id);
        // }])->get();

        // $recipe->each(function ($value, $key) use ($recipeAllergens) {
        //     return $recipeAllergens->contains($value);
        // });

        // Get User preferences
    }

    /**
     * Method to update the name of a User's fridge
     */
    public function updateName(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {

        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to add an ingredient to the Auth User's fridge
     */
    public function addIngredient(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Add the new ingredient
            Auth::user()->fridge->attach($request->id, ['amount' => $request->amount, 'measure' =>$request->measure]);
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to remove an ingredient from the Auth User's fridge
     */
    public function removeIngredient(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Remove the ingredient
            Auth::user()->fridge->detach($request->ingredID);
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to update an ingredient in the Auth User's fridge
     */
    public function update(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // update the existing ingredient
            Auth::user()->fridge->updateExistingPivot($request->id, ['amount' => $request->amount, 'measure' =>$request->measure]);
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

}
