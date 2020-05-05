<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Business extends Model
{
    //
    protected $table = "businesses";

    protected $fillable = [
      'user_id',
      'business_name',
      'business_address',
      'business_phone',
      'business_email',
      'business_details',
      'business_is_approved',
      'business_sef_url',
      'business_website',
      'business_total_likes',
      'business_total_recommended',
      'category_id',
      'bstatus_id',
      'bussiness_isapproved_by',
    ];

    protected $hidden = ['created_at','updated_at'];

    public function users(){
        return $this->belongsTo(User::class,'user_id','_id');
    }
    public function business_images()
    {
        return $this->hasMany(BusinessImage::class,'business_id','_id')->orderBy('order_id', 'asc');
    }

    public function neighborhoods()
    {
        // return $this->hasMany(Neighborhood::class,'neighborhood_id','_id');
        return $this->belongsTo(Neighborhood::class,'neighborhood_id','_id');;
    }

    public function likes()
    {
        return $this->hasMany(BusinessLike::class,'business_id','_id');
    }

    public function business_recommended()
    {
        return $this->hasMany(BusinessRecommendations::class,'business_id','_id');
    }
    
    public function categories()
    {
        return $this->belongsTo(BusinessCategory::class,'category_id','_id');
    }
    
    public function business_reports()
    {
        return $this->hasMany(BusinessReport::class,'business_id','_id');
    }
    
}
