<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

// Custom Import
use App\Http\Controllers\ProfileController;

class User extends Authenticatable {
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // Profile Model relationship
    public function profile() {
        return $this->hasOne('App\Models\Profile');
    }

    // Fridge Model relationship
    public function fridge() {
        return $this->hasOne('App\Models\Fridge');
    }

    // Recipe Model relationship
    public function recipes() {
        return $this->hasMany('App\Models\Recipe');
    }

    // Rating Model relationship
    public function ratings() {
        return $this->hasMany('App\Models\Rating');
    }

    // Function to call on the profile loadImage method
    public function profileImage() {
        return ProfileController::loadImage($this->profile_image);
    }

}
