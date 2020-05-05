<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Jenssegers\Mongodb\Eloquent\Model;


class Classified extends Model
{
    //
    protected $table = "classifieds";

    protected $fillable = ['user_id','neighborhood_id','classicat_id','classified_title','classified_description','classified_price','classified_sef_url','cstatus_id'];

    protected $hidden = ['created_at','updated_at'];

    protected $appends = ['short_price'];


    public function getshortPriceAttribute()
    {

       return number_format_short( (int)$this->attributes['classified_price']);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class,'neighborhood_id','_id');
    }

    public function classified_images()
    {
        return $this->hasMany(ClassifiedImage::class,'classified_id','_id')->orderBy('order_id', 'asc');
    }

    public function categories()
    {
        return $this->belongsTo(ClassifiedCategory::class,'classicat_id','_id');
    }

    public function offers()
    {
        return $this->hasMany(ClassifiedOffer::class,'classified_id','_id');
    }



}
