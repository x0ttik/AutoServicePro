<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


//ya esta
class Client extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];
}
