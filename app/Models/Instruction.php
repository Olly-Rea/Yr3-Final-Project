<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function recipe() {
        return $this->belongsTo('App\Models\Recipe');
    }

}
