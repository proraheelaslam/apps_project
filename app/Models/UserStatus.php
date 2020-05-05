<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class UserStatus extends Model
{
    //
    protected $table = "user_status";

    protected $hidden = [
      '_id',
    ];
}
