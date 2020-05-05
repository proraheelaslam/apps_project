<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class BusinessImage extends Model
{
    //
    protected $table = 'business_images';

    protected $fillable = [
      'business_id',
      'bimg_name',
      'type',
      'video_file',
      'order_id',
      'business_media_total_thanks',
      'business_media_total_replies'
    ];

    protected $hidden = ['created_at','updated_at','bimg_name'];

    protected $appends = ['full_business_image','full_business_video'];

    protected $casts = [
        'order_id' => 'string'
    ];

    public function getfullBusinessImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->bimg_name){
            $image_name = $this->bimg_name;
        }
        return checkBusinessImage('businesses/'.$image_name);
    }

    public function getfullBusinessVideoAttribute()
    {
        $image_name = 'no_image.png';
        if($this->video_file){
            $image_name = $this->video_file;
        }
        return checkBusinessVideo('businesses/'.$image_name);
    }
    public function thanks()
    {
        return $this->hasMany(BusinessThank::class,'media_id','_id');
    }

    public function replies()
    {
        return $this->hasMany(BusinessReply::class,'media_id','_id');
    }
}
