<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ClassifiedCategory extends Model
{
    //
    protected $table = "classified_categories";

    protected $fillable = ['classicat_name','classicat_sef_url'];

    protected $hidden = ['created_at','updated_at'];
}
