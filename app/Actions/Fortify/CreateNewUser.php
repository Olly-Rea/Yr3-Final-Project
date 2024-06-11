<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     *
     * @return User
     */
    public function create(array $input): User
    {
        // Validate the input
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Split the user name input into first and last name
        $name = explode(' ', ucwords($input['name']));
        $first_name = $name[0];
        if (\count($name) === 2) {
            $last_name = $name[1];
        } else {
            $last_name = null;
        }

        // Create the new user
        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'site_admin' => false,
        ]);
        // Create the User profile
        $user->profile()->create([
            // User name
            'first_name' => $first_name,
            'last_name' => $last_name,
            // Set as -1 to indicate an un-setup profile
            'spice_pref' => -1,
            'sweet_pref' => -1,
            'sour_pref' => -1,
            'diff_pref' => -1,
        ]);
        // Create the new Users 'fridge'
        $user->fridge()->create([
            'name' => 'Default',
        ]);

        // Create the new Users 'fridge'
        $user->cookbook()->create([
            'last_updated' => date('Y-m-d H:i:s'),
        ]);

        // return the User
        return $user;
    }
}
