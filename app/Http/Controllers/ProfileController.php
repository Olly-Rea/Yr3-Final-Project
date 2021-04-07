<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Custom Imports
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller {

    /**
     * Method to load the profile image for a 'User' model
     */
    public static function loadImage($path) {
        if($path != null) {
            $imagePath = 'storage'.DIRECTORY_SEPARATOR.$path;
            // Check the file exists, and if so, output it, otherwise, return the image placeholder
            if (file_exists(public_path().DIRECTORY_SEPARATOR.$imagePath)) {
                return asset($imagePath);
            } else {
                clearstatcache();
                return asset('images/profile-default.svg');
            }
        } else {
            clearstatcache();
            return asset('images/profile-default.svg');
        }
    }

    /**
     * Method to show a User's profile
     */
    public static function show(User $user) {
        if(Auth::check() && Auth::user()->id == $user->id) {
            return redirect('/Me');
        } else {
            return view('profile.show', ['user' => $user]);
        }
    }

}
