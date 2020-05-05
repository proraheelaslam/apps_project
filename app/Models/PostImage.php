<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class PostImage extends Model
{
    //
    protected $table = "post_images";

    protected $fillable = ['upost_id','pimg_image_file','type','video_file', 'order_id','upost_media_total_thanks','upost_media_total_replies'];
    protected $hidden = ['created_at','updated_at','pimg_image_file'];
    protected $appends = ['full_post_image','full_post_video'];
    protected $casts = [
        'order_id' => 'string'
    ];
    public function getfullPostImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->attributes['pimg_image_file']){
            $image_name = $this->attributes['pimg_image_file'];
        }
        return checkPostImage('posts/'.$image_name);

    }

    public function getfullPostVideoAttribute()
    {
        $image_name = 'no_image.png';
        if($this->video_file){
            $image_name = $this->video_file;
        }
        return checkPostVideo('posts/'.$image_name);
    }

    public function thanks()
    {
        return $this->hasMany(PostThank::class,'media_id','_id');
    }

    public function replies()
    {
        return $this->hasMany(PostReply::class,'media_id','_id');
    }

}


