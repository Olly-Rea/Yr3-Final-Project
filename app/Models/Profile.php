<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
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
        // User name
        'first_name',
        'last_name',
        // User preferences
        'spice_pref',
        'sweet_pref',
        'sour_pref',
        'diff_pref'
    ];

    // User Model relationship
    public function user() {
        return $this->hasOne('App\Models\User');
    }

}
