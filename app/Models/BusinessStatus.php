<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class BusinessStatus extends Model
{
    //
    protected $table = "business_status";

    protected $fillable = [
        'bstatus_name'
    ];
}
