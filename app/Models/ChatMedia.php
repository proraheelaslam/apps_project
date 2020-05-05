<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class ChatMedia extends Model
{
    //
    protected $table = "chat_media";

    protected $fillable = [
        'chat_id',
        'cthread_id',
        'media_url',
        'media_thumb_url',
        'type'
    ];

    protected $appends = ['full_file_path','full_thumb_path'];

    public function getfullFilePathAttribute()
    {
        $image_name = 'no_image.png';
        if($this->attributes['media_url']){
            $image_name = $this->attributes['media_url'];
        }
        return checkPostImage('chat/'.$image_name);

    }
    public function getfullThumbPathAttribute()
    {
        $image_name = 'no_image.png';
        if($this->attributes['media_thumb_url']){
            $image_name = $this->attributes['media_thumb_url'];
        }
        return checkPostImage('chat/'.$image_name);

    }
}
