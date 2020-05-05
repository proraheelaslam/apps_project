<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class UserApplicationSetting extends Model
{
    //

    protected $table = "user_application_settings";

    protected $fillable = [
        'setting_name',
        'setting_key',
        'setting_value'
    ];

    protected $hidden = ['created_at','updated_at'];
}
