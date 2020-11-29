<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Custom import
use App\Models\Recipe;

class RecipeController extends Controller
{
    // Method to show all recipes (paginated)
    public function index() {
        return view('feed', ['recipes' => Recipe::paginate(30)]);
    }


}
