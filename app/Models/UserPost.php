<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use MongoDB\BSON\UTCDatetime;
use Jenssegers\Mongodb\Eloquent\Model;


class UserPost extends Model
{
    //

    protected $table =  "user_posts";
    protected $fillable = [
        'user_id',
        'neighborhood_id',
        'post_type',
        'pcat_id',
        'upost_title',
        'upost_description',
        'upost_total_thanks',
        'upost_total_replies',
        'upost_poll_end_date',
        'upost_sef_url',
        'pstatus_id',
        'is_edited'
    ];

    protected $hidden = ['created_at','updated_at','pcat_id','views'];
    protected $appends = ['post_time','post_date'];

    public function getpostTimeAttribute()
    {
         return  (new Carbon($this->created_at))->diffForHumans();

    }

    public function getpostDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('M d,Y');
    }
    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class,'neighborhood_id','_id');
    }

    public function post_images()
    {
        return $this->hasMany(PostImage::class,'upost_id','_id')->orderBy('order_id', 'ASC');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }

    public function thanks()
    {
        return $this->hasMany(PostThank::class,'upost_id','_id');
    }

    public function replies()
    {
        return $this->hasMany(PostReply::class,'upost_id','_id');
    }

    public function post_questions()
    {
        return $this->hasMany(PostQuestion::class,'upost_id','_id');
    }

    public function answers(){
        return $this->hasMany(PostAnswer::class,'upost_id','_id');
    }

    public function views()
    {
        return $this->hasMany(ViewPost::class,'upost_id','_id');
    }


}
