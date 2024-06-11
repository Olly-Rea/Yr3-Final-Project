<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CookBook extends Model
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
        'last_updated',
    ];

    // User Model relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Recipe Model relationship
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'cookbook_recipes');
    }
}
