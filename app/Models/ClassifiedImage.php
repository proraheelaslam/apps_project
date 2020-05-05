<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ClassifiedImage extends Model
{
    //
    protected $table = "classified_images";

    protected $fillable = [
        'classified_id',
        'cimg_image_file',
        'type',
        'order_id',
        'classified_media_total_thanks',
        'classified_media_total_replies'
    ];

    protected $hidden = ['created_at','updated_at','cimg_image_file'];

    protected $appends = ['full_classified_image'];

    protected $casts = [
        'order_id' => 'string'
    ];
    public function getfullClassifiedImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->cimg_image_file){
            $image_name = $this->cimg_image_file;
        }
        return checkClassifiedImage('classifieds/'.$image_name);
    }
    public function thanks()
    {
        return $this->hasMany(ClassifiedThank::class,'media_id','_id');
    }

    public function replies()
    {
        return $this->hasMany(ClassifiedReply::class,'media_id','_id');
    }
}
