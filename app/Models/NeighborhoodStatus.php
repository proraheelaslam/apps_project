<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class NeighborhoodStatus extends Model
{
    //
    protected $table = "neighborhood_status";
    protected $primaryKey = "nstatus_id";

    protected $hidden = ['_id'];

}
