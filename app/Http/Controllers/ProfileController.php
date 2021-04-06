<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller {

    /**
     * Method to show a single user profile poage
     */
    public function show(User $user) {
        return view('profile.show', ['user' => $user]);
    }

}
