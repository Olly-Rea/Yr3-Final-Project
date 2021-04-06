<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model {
    use HasFactory;

    // State a lack of timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    // // Ingredient Model relationship
    // public function ingredients() {
    //     return $this->belongsToMany('App\Models\Ingredient');
    // }

    /**
     * Parent model relationship
     */
    public function labelable() {
        return $this->morphTo();
    }
}
