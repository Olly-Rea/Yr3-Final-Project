<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
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
        'name',
        'url',
    ];

    protected $casts = [
        'references' => 'array'
    ];

    // Ingredient (alternative) Model relationship
    public function alternatives() {
        return $this->belongsToMany('App\Models\Ingredient', 'alternatives', 'altrnt_id', 'ingred_id');
    }

    // 'Category' Model relationship
    public function categories() {
        return $this->morphToMany('App\Models\Category', 'categorical');
    }
    // 'Label' Model relationship
    public function labels() {
        return $this->morphToMany('App\Models\Label', 'labelable');
    }

    // 'Allergen' Model relationship
    public function allergens() {
        return $this->belongsToMany('App\Models\Allergen', 'ingredient_allergens');
    }

    // 'Trace' Model relationship
    public function traces() {
        return $this->belongsToMany('App\Models\Trace', 'ingredient_traces');
    }

    // Recipe Model relationship
    public function recipes() {
        return $this->belongsToMany('App\Models\Recipe', 'recipe_ingredients');
    }

    // Characteristic Model relationship
    public function characteristics() {
        return $this->belongsToMany('App\Models\Characteristic');
    }

}
