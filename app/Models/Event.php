<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Query\Builder;


class Event extends Model
{
    //
    protected $table = "events";

    protected $fillable = [

        'user_id',
        'neighborhood_id',
        'event_title',
        'event_description',
        'event_locations',
        'event_date',
        'event_time',
        'event_total_joining',
        'event_total_maybe',
        'estatus_id',
        'is_notify_rsvp'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $appends = ['is_event_ended'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class,'neighborhood_id','_id');
    }

    public function event_images()
    {
        return $this->hasMany(EventImage::class,'event_id','_id')->orderBy('order_id', 'asc');
    }

    public function categories()
    {
        return $this->belongsTo(EventCategory::class,'ecategory_id','_id');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class,'event_id','_id');
    }

    public function getisEventEndedAttribute() {
        return  ($this->event_date < Carbon::today()->toDateString() ? true : false);
    }

}
