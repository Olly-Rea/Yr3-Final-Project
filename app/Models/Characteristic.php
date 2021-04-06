<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model {
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
        'description',
        'intensity' // TODO Put in pivot table (and find way to access)
    ];

    // Ingredient Model relationship
    public function ingredients() {
        return $this->belongsToMany('App\Models\Ingredient');
    }

}
