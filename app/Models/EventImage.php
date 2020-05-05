<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class EventImage extends Model
{
    //
    protected $table = "event_images";
    protected $fillable = ['event_id','eimg_image_file','video_file','type', 'order_id','event_media_total_thanks','event_media_total_replies'];

    protected $visible = ['_id','event_id','video_file' , 'type','full_event_image','full_event_video' , 'order_id','event_media_total_thanks','event_media_total_replies','is_liked'];

    protected $appends = ['full_event_image','full_event_video'];

    protected $casts = [
        'order_id' => 'string'
    ];
    public function getfullEventImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->eimg_image_file){
            $image_name = $this->eimg_image_file;
        }
        return checkEventImage('events/'.$image_name);
    }

    public function getfullEventVideoAttribute()
    {
        $image_name = 'no_image.png';
        if($this->video_file){
            $image_name = $this->video_file;
        }
        return checkEventVideo('events/'.$image_name);
    }

    /*public function getisLikedAttribute()
    {
        
        return '';
    }
*/
    public function thanks()
    {
        return $this->hasMany(EventThank::class,'media_id','_id');
    }

    public function replies()
    {
        return $this->hasMany(EventReply::class,'media_id','_id');
    }

}
