<?php

namespace App\Http\Controllers;

use App\Models\Category;

class StartController extends Controller
{
    /**
     * Method to load the start page(s).
     */
    public function start()
    {
        return view('welcome', ['categories' => Category::all()]);
    }
}
