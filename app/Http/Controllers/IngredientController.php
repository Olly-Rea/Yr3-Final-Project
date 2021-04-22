<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller {

    /**
     * Method to show a single Ingredient poage
     */
    public function show(Ingredient $ingredient) {
        return view('ingredient', ['ingredient' => $ingredient]);
    }

}
