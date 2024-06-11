<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
        'references' => 'array',
    ];

    // Ingredient (alternative) Model relationship
    public function alternatives(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'alternatives', 'alternative_id', 'ingredient_id');
    }

    // 'Category' Model relationship
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorical');
    }

    // 'Label' Model relationship
    public function labels(): MorphToMany
    {
        return $this->morphToMany(Label::class, 'labelable');
    }

    // 'Allergen' Model relationship
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class, 'ingredient_allergens');
    }

    // 'Trace' Model relationship
    public function traces(): BelongsToMany
    {
        return $this->belongsToMany(Trace::class, 'ingredient_traces');
    }

    // Recipe Model relationship
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients');
    }

    // Characteristic Model relationship
    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class);
    }

    // // Fridge Model relationship
    // public function fridges() {
    //     return $this->belongsToMany(Fridge::class, 'fridge_ingredients');
    // }
}
