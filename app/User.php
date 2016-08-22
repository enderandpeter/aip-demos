<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * The main User class that all others are based on.
 * 
 * All web applications in the site that have user registration should have a model class that
 * extends this one. 
 * 
 * @author Spencer
 */
abstract class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
}
