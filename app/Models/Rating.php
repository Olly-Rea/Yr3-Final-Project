<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Recipe Model relationship
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

}
