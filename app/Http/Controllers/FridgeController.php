<?php

namespace App\Http\Controllers;

// Custom imports
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FridgeController extends Controller {

    /**
     * Method to update the name of a User's fridge
     */
    public function updateName(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Update the fridge name
            Auth::user()->fridge->update([
                'name' => $request->name
            ]);
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
