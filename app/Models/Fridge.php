<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fridge extends Model {
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
        'name'
    ];

    // User Model relationship
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Ingredient Model relationship
    public function ingredients() {
        return $this->belongsToMany('App\Models\Ingredient', 'fridge_ingredients')
            ->withPivot('amount', 'measure');
    }

}
