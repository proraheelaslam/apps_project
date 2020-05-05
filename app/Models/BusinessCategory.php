<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BusinessCategory extends Model
{
    //
    protected $table = 'business_categories';

    protected $fillable = [
      'name'
    ];

}
