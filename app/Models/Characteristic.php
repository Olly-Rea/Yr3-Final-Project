<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Characteristic extends Model
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
        'description',
        'intensity' // TODO Put in pivot table (and find way to access)
    ];

    // Ingredient Model relationship
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

}
