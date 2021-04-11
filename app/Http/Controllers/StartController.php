<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartController extends Controller {

    /**
     * Method to load the start page(s)
     */
    public function start() {
        return view('welcome');
    }

}
