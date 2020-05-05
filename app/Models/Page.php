<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Page extends Model
{
    //
    protected $table = "pages";

    protected $fillable = [
        'title',
        'description',
        'sef_url',
        'page_key'
    ];

    protected $hidden = [
      '_id',
      'created_at',
      'updated_at'
    ];
}
