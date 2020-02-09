<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model {

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_score', 'machine_score', 'user_won'
    ];

    
}
