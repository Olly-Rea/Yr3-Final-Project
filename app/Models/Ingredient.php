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
        'products',
        'same_as'
    ];

    public function alternatives() {
        return $this->belongsToMany('App\Models\Ingredient', 'alternatives');
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
