<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Recipe;

class SearchController extends Controller {


    // TODO Add search suggestion functionality (show first 10 ingredient/recipes for currently typed in string)

    /**
     * Method to return the results of a search bar query
     */
    public function search(Request $request) {
        // Check that the request is AJAX
        if ($request->ajax()) {

            $pagination = 20;

            // Get any relevent recipes/ingredients from the databases
            $recipes = Recipe::where("name", "LIKE", "%".$request->search."%")->limit($pagination);//->where('last_name', 'LIKE', $request->search . '%')->get();
            $ingredients = Ingredient::where("name", "LIKE", "%".$request->search . "%")->limit($pagination);//->where('last_name', 'LIKE', $request->search . '%')->get();

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


}
