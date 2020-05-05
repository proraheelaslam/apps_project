<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class PostCategory extends Model
{
    //
    protected $table = "post_categories";

    protected $fillable = [
      'pcat_name'
    ];
}
