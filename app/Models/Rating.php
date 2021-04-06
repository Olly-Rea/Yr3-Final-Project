<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating_type',
        'value'
    ];

    // User Model relationship
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Recipe Model relationship
    public function recipe() {
        return $this->belongsTo('App\Models\Recipe');
    }

    // SpiceRating Model relationship
    public function spice() {
        return $this->hasOne('App\Models\Ratings\SpiceRating');
    }
    // SweetRating Model relationship
    public function sweet() {
        return $this->hasOne('App\Models\Ratings\SweetRating');
    }
    // SourRating Model relationship
    public function sour() {
        return $this->hasOne('App\Models\Ratings\SourRating');
    }

}
