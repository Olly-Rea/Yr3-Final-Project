<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Ingredient Model relationship
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients', 'recipe_id', 'ingredient_id')
            ->withPivot('misc_info', 'amount', 'measure');
    }

    // Instruction Model relationship
    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class);
    }

    // Rating Model relationship
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // // Method to get any allergens a Recipe might contain
    // public function allergens() {
    //     return $this->hasManyThrough(Allergen::class, Ingredient::class);
    // }
    // // Method to get any traces a Recipe might contain
    // public function traces() {
    //     return $this->hasManyThrough(Trace::class, Ingredient::class);
    // }

}
