<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Ad extends Model
{
    //

    protected $table = "ads";

    protected $fillable = [
      'name',
      'ads',
      'unit_id',
      'app_id',
      'height',
      'width'
    ];

    protected $hidden = ['created_at','updated_at'];
}
