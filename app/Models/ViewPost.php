<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ViewPost extends Model
{
    //
    protected $table = "view_posts";

    protected $fillable = [
        'user_id',
        'upost_id',
        'view_date_time'
    ];



    protected $hidden = [
      'created_at',
      'updated_at'
    ];

}
