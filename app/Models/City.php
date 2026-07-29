<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $table = 'cities';
    protected $fillable = [
        'name',
    ];

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }
}
