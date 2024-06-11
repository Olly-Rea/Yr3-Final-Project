<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fridge extends Model
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
        'user_id',
        'name',
    ];

    // User Model relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Ingredient Model relationship
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'fridge_ingredients')
            ->withPivot('amount', 'measure');
    }
}
