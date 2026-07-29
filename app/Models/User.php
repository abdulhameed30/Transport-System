<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'city_id'
    ];


    protected $hidden = [
        'password'
    ];
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
}
