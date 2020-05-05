<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class AppSetting extends Model
{
    //
    protected $table = "app_settings";

    protected $fillable = [
      'app_setting_name',
      'app_setting_key',
    ];
}
