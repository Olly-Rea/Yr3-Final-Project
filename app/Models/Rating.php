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
        // Rating values
        'spice_value',
        'sweet_value',
        'sour_value',
        'difficulty_value',
        'time_taken',
        // 'Overall feeling' rating
        'out_of_five'
    ];

    // User Model relationship
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Recipe Model relationship
    public function recipe() {
        return $this->belongsTo('App\Models\Recipe');
    }

}
