<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class EventParticipant extends Model
{
    //
    protected $table = "event_participants";

    protected $fillable = [
      'event_id',
      'user_id',
      'epart_children',
      'epart_adults',
      'participation_type',
      'epart_comment',
      'sef_url',
    ];
    protected $hidden = ['created_at','updated_at'];


    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }


}
