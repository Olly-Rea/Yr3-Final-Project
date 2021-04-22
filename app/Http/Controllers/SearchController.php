<?php

namespace App\Http\Controllers;

// Custom imports

use App\Models\Allergen;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Recipe;

class SearchController extends Controller {

    private $pagination = 20;

    // TODO Add search suggestion functionality (show first 10 ingredient/recipes for currently typed in string)

    /**
     * Method to return the results of a search bar query
     */
    public function search(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {

            // Get any relevent recipes/ingredients from the databases
            $recipes = Recipe::where("name", "LIKE", "%".$request->search."%")->limit($this->pagination);//->where('last_name', 'LIKE', $request->search . '%')->get();
            $ingredients = Ingredient::where("name", "LIKE", "%".$request->search . "%")->limit($this->pagination);//->where('last_name', 'LIKE', $request->search . '%')->get();

            // Split the results based on the number of results returned per collection
            $recipeCount = (count($ingredients->get()) > 5) ? 5 : 10 - count($ingredients->get());
            $ingredCount = (count($recipes->get()) > 5) ? 5 : 10 - count($recipes->get());

            // Get the top 10 results (merged / 'concatenated')
            $results = $recipes->paginate($recipeCount)->concat($ingredients->paginate($ingredCount));

            // dd($results);

            // return the paginated list of recipes matching the user's search
            return view('components.search-result', ['results' => $results])->render();

        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to return the results from an allergen search
     */
    public function allergen(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {
            // Get the results from the query
            $results = Allergen::where("name", "LIKE", "%".$request->search."%")->limit($this->pagination)->get();

            // dd($results);

            // return the paginated list of recipes matching the user's search
            return view('components.item-container', ['results' => $results])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to return the results from an ingredient search
     */
    public function ingredient(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {
            // Get the results from the query
            $results = Ingredient::where("name", "LIKE", "%".$request->search . "%")->limit($this->pagination)->get();//->where('last_name', 'LIKE', $request->search . '%')->get();

            // dd($results);

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
