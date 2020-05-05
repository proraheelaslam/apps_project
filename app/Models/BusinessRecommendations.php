<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class BusinessRecommendations extends Model
{
    //
    protected $table = 'business_recommendations';

    protected $fillable = [
      'business_id',
      'user_id'
    ];

    protected $hidden = ['_id','created_at','updated_at'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }
}
