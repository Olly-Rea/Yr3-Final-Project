<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\RateLimited;

// Custom imports
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Recipe;

class CookbookContainer {

    /**
     * Create a new container instance.
     *
     * @return void
     */
    public function __construct() {
        $this->refresh(false);
    }

    /**
     * Method to show the generated recipe
     */
    public function getRecipes($refresh) {
        if ($refresh) $this->refresh($refresh);
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
    public function refresh($forceRefresh) {
        // Check if a user is logged in
        if(Auth::check()) {
            // Check if the user has request a refresh OR if the cookbook was last updated over a week ago (OR if the cookbook is empty)
            if ($forceRefresh || (Auth::user()->cookbook->last_updated < date("Y-m-d", strtotime("-7 days")) || count(Auth::user()->cookbook->recipes) == 0))
                $this->getPersonalised();
        } else {
            // Start a session (if one has not already been started)
            if (session_status() === PHP_SESSION_NONE) session_start();
            // Check if the user has request a refresh OR if no recipes currently added to recipes (OR if 'recipes' is empty)
            if ($forceRefresh || !isset($_SESSION["recipes"]) || count($_SESSION["recipes"]) == 0 ) {
                $this->getRandom();
            }
        }
    }

    /**
     * Method to get new recipes (based on user prefs)
     */
    public function getPersonalised() {
        // Get the Users fridge ingredients and preferences
        $userIngredients = Auth::user()->fridge->ingredients->pluck('id');
        $userPrefs = Auth::user()->profile;
        $userAllergens = Auth::user()->profile->allergens;

        DB::enableQueryLog(); // Enable query log

        // Eager load the Recipes relations
        $recipes = Recipe::with('ingredients', 'ratings');
        // Filter out recipes not matching User preferences (not quite a boolean AND, but does try and match the closest recipes to user prefs)
        $recipes = $recipes->whereHas('ratings', function (Builder $query) use ($userPrefs) {
            $query->where(function ($q) {$q->selectRaw('AVG(spice_value)');}, '<=', $userPrefs->spice_pref)
                ->where(function ($q) {$q->selectRaw('AVG(sweet_value)');}, '<=', $userPrefs->sweet_pref)
                ->where(function ($q) {$q->selectRaw('AVG(sour_value)');}, '<=', $userPrefs->sour_pref)
                ->where(function ($q) {$q->selectRaw('AVG(difficulty_value)');}, '<=', $userPrefs->diff_pref);
        });
        // If the User has more than 3 ingredients in their fridge, execute this part of the query
        if (count($userIngredients) > 3) {
            // Get recipes containing up to the number of the ingredients the user has
            $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userIngredients)  {
                $query->whereIn('id', $userIngredients);
            }, '<=', count($userIngredients));
        }

        // // Filter out any recipes containing the Users allergens first
        // $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userAllergens) {
        //     $query->whereIn('allergens', $userAllergens);
        // });

        // $finalRecipes = collect();

        // foreach($recipes->get() as $recipe) {

        //     $spiceVal = $recipe->ratings->avg('spice_val') <= $userPrefs->spice_pef;
        //     $sweetVal = $recipe->ratings->avg('$sweet_val') <= $userPrefs->sweet_pref;
        //     $sourVal = $recipe->ratings->avg('sour_val') <= $userPrefs->sour_pref;
        //     $diffVal = $recipe->ratings->avg('diff_val') <= $userPrefs->diff_pref;

        //     if($spiceVal && $sweetVal && $sourVal && $diffVal) {
        //         $finalRecipes->push($recipe);
        //     }
        // }

        // Randomise the order and take 7
        $recipes = $recipes->inRandomOrder()->limit(7)->get()->pluck('id');

        // dd(DB::getQueryLog()); // Show results of log


        Auth::user()->cookbook->recipes()->sync($recipes);
        // Update the cookbook last_updated time
        Auth::user()->cookbook()->update([
            'last_updated' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Method to get recipes at random for a Guest User
     */
    public function getRandom() {
        $_SESSION["recipes"] = Recipe::all()->count() > 7 ? Recipe::all()->random(7) : Recipe::get();
    }

}
