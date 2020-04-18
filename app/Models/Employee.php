<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guard =[
        'id'
    ];

    protected $fillable =[
        'name','salary','age','profile_image'
    ];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}
