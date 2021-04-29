<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use App\Models\{Ingredient, Recipe, Allergen};
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller {

    private $pagination = 20;

    // TODO Add search suggestion functionality (show first 10 ingredient/recipes for currently typed in string)

    /**
     * Method to return the results of a search bar query
     */
    public function search(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {

            // Remove 's' at the end of the string (for plurals)
            $searchStr = strtolower(substr($request->search, -1)) == 's' ? substr($request->search, 0, -1) : $request->search;

            // Get any relevent recipes/ingredients from the databases
            $recipes = Recipe::where("name", "LIKE", "%".$searchStr."%")->limit($this->pagination);
            $ingredients = Ingredient::where("name", "LIKE", $searchStr."%")->limit($this->pagination);

            // Split the results based on the number of results returned per collection
            $recipeCount = (count($ingredients->get()) > 5) ? 5 : 10 - count($ingredients->get());
            $ingredCount = (count($recipes->get()) > 5) ? 5 : 10 - count($recipes->get());

            // Get the top 10 results (merged / 'concatenated')
            $results = $recipes->paginate($recipeCount)->concat($ingredients->paginate($ingredCount));

            // return the paginated list of recipes matching the user's search
            return view('components.search-result', ['results' => $results])->render();

        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to return the results from an allergen search (not including ones the user has already indicated)
     */
    public function allergen(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {
            // Remove 's' at the end of the string (for plurals)
            $searchStr = strtolower(substr($request->search, -1)) == 's' ? substr($request->search, 0, -1) : $request->search;
            // Get the results from the query
            $results = Allergen::where("name", "LIKE", $searchStr."%")
                ->whereNotIn('id', Auth::user()->profile->allergens->map(function ($item, $key) { return $item->id; }))
                ->limit($this->pagination)->get();
            // return the paginated list of recipes matching the user's search
            return view('components.item-container', ['results' => $results])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to return the results from an ingredient search (not including ones the user has already indicated)
     */
    public function ingredient(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {
            // Remove 's' at the end of the string (for plurals)
            $searchStr = strtolower(substr($request->search, -1)) == 's' ? substr($request->search, 0, -1) : $request->search;
            // Get the results from the query
            $results = Ingredient::where("name", "LIKE", $searchStr."%");
            // Check if the results should exclude ingredients the User has
            if ($request->forUser) {
                $results = $results->whereNotIn('id', Auth::user()->fridge->ingredients->map(function ($item, $key) { return $item->id; }));
            }
            $results = $results->limit($this->pagination)->get();
            // return the paginated list of recipes matching the user's search
            return view('components.item-container', ['results' => $results])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to return the results from a recipe search
     */
    public function recipe(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {
            // Get the results from the query
            $results = Recipe::where("name", "LIKE", "%".$request->search."%")->limit($this->pagination);
            // return the paginated list of recipes matching the user's search
            return view('components.item-container', ['results' => $results])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }


}
