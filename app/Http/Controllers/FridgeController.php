<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use App\Models\Ingredient;

class FridgeController extends Controller {

    /**
     * Method to add an ingredient to the Auth User's fridge
     */
    public function add(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {


        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to remove an ingredient from the Auth User's fridge
     */
    public function remove(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {


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


        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

}
