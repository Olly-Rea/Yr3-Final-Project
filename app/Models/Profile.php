<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;

    // State a lack of timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        // User name
        'first_name',
        'last_name',
        // User preferences
        'spice_pref',
        'sweet_pref',
        'sour_pref',
        'diff_pref',
    ];

    // User Model relationship
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    // Allergen model relationship
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class, 'profile_allergens');
    }
}
