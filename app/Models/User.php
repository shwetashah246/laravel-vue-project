<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 
    ];

    
}
