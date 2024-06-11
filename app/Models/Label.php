<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Label extends Model
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
    ];

    /**
     * Ingredient Model relationship.
     */
    public function ingredients(): MorphToMany
    {
        return $this->morphToMany(
            related: Ingredient::class,
            name: 'labelable',
            foreignPivotKey: 'label_id',
            relatedPivotKey: 'labelable_id',
            inverse: true
        );
    }
}
