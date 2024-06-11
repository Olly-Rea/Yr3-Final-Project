<?php

namespace App\Models;

use App\Http\Controllers\ProfileController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
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
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    // Fridge Model relationship
    public function fridge(): HasOne
    {
        return $this->hasOne(Fridge::class);
    }

    // CookBook Model relationship
    public function cookbook(): HasOne
    {
        return $this->hasOne(CookBook::class);
    }

    // Recipe Model relationship
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    // Rating Model relationship
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->orderBy('created_at');
    }

    // Function to call on the profile loadImage method
    public function profileImage()
    {
        return ProfileController::loadImage($this->profile->id);
    }
}
