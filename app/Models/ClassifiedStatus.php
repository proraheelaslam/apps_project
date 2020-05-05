<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ClassifiedStatus extends Model
{
    //
    protected $table = "classifed_status";

    protected $fillable = ['cstatus_name'];


}
