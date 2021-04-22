<?php

namespace App\Http\Controllers;


// Custom Imports
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    /**
     * Method to show the Auth User's profile
     */
    public static function me() {
        if(Auth::check()) {
            // Check if the User has just been created
            return view('profile.show', ['user' => Auth::user()]);
        } else {
            abort(404);
        }
    }

    /**
     * Method to show the Auth User's profile
     */
    public static function getStarted() {
        if(Auth::check()) {
            // Check if the User has just been created
            return view('profile.get-started', ['user' => Auth::user()]);
        }
    }

    /**
     * Method to update a User's profile prferences
     */
    public function updatePrefs(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Update the Users preferences
            Auth::user()->profile->update([
                'spice_pref' => $request->spice,
                'sweet_pref' => $request->sweet,
                'sour_pref' => $request->sour,
                'diff_pref'=> $request->diff
            ]);
        } else {
            abort(404);
        }
    }

}
