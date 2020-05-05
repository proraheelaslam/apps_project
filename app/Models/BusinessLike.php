<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class BusinessLike extends Model
{
    //
    protected $table = 'business_likes';

    protected $fillable = [
      'business_id',
      'user_id'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function users(){
    	return $this->belongsTo(User::class, "user_id", "_id");
    }
}
