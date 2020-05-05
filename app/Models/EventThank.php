<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class EventThank extends Model
{
    //
    protected $table = "event_thanks";
    protected $fillable = ['event_id','media_id','user_id','is_image_thank'];

    protected $hidden = ['_id','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
