<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\RateLimited;

// Custom imports
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Recipe;

class CookBookContainer {

    /**
     * Create a new container instance.
     *
     * @return void
     */
    public function __construct() {
        $this->refresh();
    }

    /**
     * Method to show the generated recipe
     */
    public function getRecipes() {
        if (Auth::check()) {
            // Return the current users cookbook
            return Auth::user()->cookbook->recipes;
        } else {
            // Return the guests temporary cookbook
            return $_SESSION["recipes"];
        }
    }

    /**
     * Method to refresh the list of recipes shown
     */
    public function refresh() {
        // Check if a user is logged in AND if the cookbook was last updated over a week ago (OR if the cookbook is empty)
        if(Auth::check() && (Auth::user()->cookbook->last_updated < date("Y-m-d", strtotime("-7 days")) || count(Auth::user()->cookbook->recipes) == 0)) {
            // Get some new recipes (based on user prefs)

            // Get the Users fridge ingredients and preferences
            $userIngredients = Auth::user()->fridge->ingredients->pluck('id');
            $userPrefs = Auth::user()->profile;
            $userAllergens = Auth::user()->profile->allergens;

            // Eager load the Recipes relations
            $recipes = Recipe::with('ingredients', 'ratings');

            // TODO Check that this gets recipes that contain ALL of OR at least 3 of a users ingredients

            // If the User has more than 3 ingredients in their fridge, execute this part of the query
            if (count($userIngredients) > 3) {
                // Get recipes containing at least 3 ingredients the user has
                $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userIngredients)  {
                    $query->whereIn('id', $userIngredients);
                }, '>=', 3);
            }

            // // Filter out any recipes containing the Users allergens first
            // $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userAllergens) {
            //     $query->whereIn('allergens', $userAllergens);
            // });

            // Then get User preferences
            $recipes = $recipes->whereHas('ratings', function (Builder $query) use ($userPrefs) {
                // $query->where($ratings->avg('spice_value'), '<=', $userPrefs->spice_pref)
                $query->where('spice_value', '<=', $userPrefs->spice_pref)
                    ->where('sweet_value', '<=', $userPrefs->sweet_pref)
                    ->where('sour_value', '<=', $userPrefs->sour_pref)
                    ->where('difficulty_value', '<=', $userPrefs->diff_pref);
            });

            // Randomise the order and take 7
            $recipes = $recipes->inRandomOrder()->limit(7)->get();

            // dd($recipes);

            // Detach all of the previous recipes
            Auth::user()->cookbook->recipes()->detach();
            // Attach the new recipes
            foreach($recipes as $recipe) {
                Auth::user()->cookbook->recipes()->attach($recipe);
            }

        } else {
            // Start a session (if one has not already been started)
            if (session_status() === PHP_SESSION_NONE) session_start();
            // Add a random selection of recipes to the Guest's Session (if none already added)
            if (!isset($_SESSION["recipes"])) {
                $_SESSION["recipes"] = Recipe::all()->count() > 7 ? Recipe::all()->random(7) : Recipe::get();
            }
        }
    }

}
