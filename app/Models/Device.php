<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Device extends Model
{
    //
    protected $table = 'devices';

    protected $fillable = [

        'device_id',
        'token',
        'type',
        'app_mode',
        'user_id'
    ];


}
