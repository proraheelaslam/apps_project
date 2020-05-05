<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class UserSetting extends Model
{
    //
    protected $table = 'user_settings';
    protected $fillable = [
        'user_id',
        'app_setting_id'
    ];
}
