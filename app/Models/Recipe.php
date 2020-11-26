<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'products',
        'same_as'
    ];

    // User Model relationship
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Ingredient Model relationship
    public function ingredients() {
        return $this->belongsToMany('App\Models\Ingredient', 'recipe_ingredients');
    }

    // Instruction Model relationship
    public function instructions() {
        return $this->hasMany('App\Models\Instruction');
    }

}
