<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class EventStatus extends Model
{
    //
    protected $table = 'event_status';
    protected $fillable = ['estatus_name'];
}
