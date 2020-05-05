<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Gender extends Model
{
    //
    protected $table = "gender";
    protected $primaryKey = "gender_id";

    protected $hidden = [
      '_id',
      'gender_key'
    ];
}
