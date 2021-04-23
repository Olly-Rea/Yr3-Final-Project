<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'serves'
    ];

    // User Model relationship
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Ingredient Model relationship
    public function ingredients() {
        return $this->belongsToMany('App\Models\Ingredient', 'recipe_ingredients', 'recipe_id', 'ingred_id')
            ->withPivot('misc_info', 'amount', 'measure');
    }

    // Instruction Model relationship
    public function instructions() {
        return $this->hasMany('App\Models\Instruction');
    }

    // Rating Model relationship
    public function ratings() {
        return $this->hasMany('App\Models\Rating');
    }

    // // Method to get any allergens a Recipe might contain
    // public function allergens() {
    //     return $this->hasManyThrough('App\Models\Allergen', 'App\Models\Ingredient');
    // }
    // // Method to get any traces a Recipe might contain
    // public function traces() {
    //     return $this->hasManyThrough('App\Models\Trace', 'App\Models\Ingredient');
    // }

}
