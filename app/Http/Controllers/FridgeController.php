<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use App\Models\{Recipe, Ingredient};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FridgeController extends Controller {

    /**
     * Method to retrieve a recipe based on the ingredients in a Users fridge
     */
    public function recipeFrom() {

        // Get the Users fridge ingredients and preferences
        $userIngredients = Auth::user()->fridge->ingredients;
        $userPrefs = Auth::user()->profile;

        $recipes = Recipe::with('ingredients', 'ratings');

        // TODO Check that this gets recipes that contain ALL of OR at least 3 of a users ingredients

        // If the User has ingredients in their fridge, execute this part of the query
        if (count($userIngredients)) {
            // Get recipes containing at least 3 ingredients the user has
            $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userIngredients)  {
                $query->whereIn('name', $userIngredients);
            }, '>=', 3);
        }

        // Get User preferences
        $recipes = $recipes->whereHas('ratings', function (Builder $query) use ($userPrefs) {
            // $query->where($ratings->avg('spice_value'), '<=', $userPrefs->spice_pref)
            $query->where('spice_value', '<=', $userPrefs->spice_pref)
                ->where('sweet_value', '<=', $userPrefs->sweet_pref)
                ->where('sour_value', '<=', $userPrefs->sour_pref)
                ->where('difficulty_value', '<=', $userPrefs->diff_pref);
        });

        // Filter out any recipes containing the Users allergens
        $recipes = $recipes->whereHas('ratings', function (Builder $query) use ($userPrefs) {
            // $query->where($ratings->avg('spice_value'), '<=', $userPrefs->spice_pref)
            $query->where('spice_value', '<=', $userPrefs->spice_pref)
                ->where('sweet_value', '<=', $userPrefs->sweet_pref)
                ->where('sour_value', '<=', $userPrefs->sour_pref)
                ->where('difficulty_value', '<=', $userPrefs->diff_pref);
        });

        // // Get the shared allergens and/or traces
        // $userAllergens = Auth::user()->profile->allergens->diff(
        //     Auth::user()->profile->allergens->diff($recipeAllergens)
        // );

        // Randomise the order and take 7
        $recipes = $recipes->inRandomOrder()->limit(7)->get();

        // dd($recipes);

        return view('feed', ['recipes' => $recipes]);
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
    public function addTo(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Add the new ingredient
            Auth::user()->fridge->ingredients()->attach($request->id, ['amount' => 1]);
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to remove an ingredient from the Auth User's fridge
     */
    public function removeFrom(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Remove the ingredient
            Auth::user()->fridge->ingredients()->detach($request->id);
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
            Auth::user()->fridge->ingredients()->updateExistingPivot($request->id, ['amount' => $request->amount, 'measure' =>$request->measure]);
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

}
