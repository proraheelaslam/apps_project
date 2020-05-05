<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class EventCategory extends Model
{
    //
    protected $table = "event_categories";

    protected $fillable = ['name'];


}
