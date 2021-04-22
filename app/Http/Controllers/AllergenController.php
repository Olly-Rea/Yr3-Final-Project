<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllergenController extends Controller {

    /**
     * Method to add a known allergen to a User's profile
     */
    public function addTo(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Attach the new allergen to the User's allergens
            Auth::user()->profile->allergens()->attach($request->id);
        } else {
            abort(404);
        }
    }

    /**
     * Method to remove a known allergen from a User's profile
     */
    public function removeFrom(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Attach the new allergen to the User's allergens
            Auth::user()->profile->allergens()->detach($request->id);
        } else {
            abort(404);
        }
    }

}
