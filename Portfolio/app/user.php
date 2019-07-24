<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       '_token', 'first_name','last_name', 'email', 'password','address','city','state','zip','image',
    ];

}
