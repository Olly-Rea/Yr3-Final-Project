<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instruction extends Model
{
    use HasFactory;

    // State a lack of timestamps
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'content'
    ];

    // Recipe Model relationship
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

}
