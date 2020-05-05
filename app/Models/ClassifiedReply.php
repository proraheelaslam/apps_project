<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class ClassifiedReply extends Model
{
    //
    protected $table = "classified_replies";

    protected $fillable = [
        'classified_id',
        'media_id',
        'user_id',
        'preply_comment',
        'is_edited',
        'is_image_comment'
    ];
    protected $hidden = [
      'created_at',
      'updated_at'
    ];

    protected $appends = ['comment_time'];

    public function getcommentTimeAttribute()
    {
        return  (new Carbon($this->created_at))->diffForHumans();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }

}