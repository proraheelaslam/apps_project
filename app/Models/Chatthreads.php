<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Chatthreads extends Model
{
    //
    protected $table = "chat_threads";

    protected $fillable = [
        'chat_id',
        'cthread_from_user_id',
        'cthread_to_user_id',
        'cthread_message',
        'cthread_is_deleted_from',
        'cthread_is_deleted_to',
        'cthread_created_at',
        'time',
    ];

    protected $appends = ['full_file_path'];

    public function getfullFilePathAttribute()
    {
        $image_name = 'no_image.png';
        if($this->attributes['cthread_message']){
            $image_name = $this->attributes['cthread_message'];
        }
        return checkPostImage('chat/'.$image_name);

    }

    public function from_user()
    {
        return $this->belongsTo(User::class,'cthread_from_user_id','_id');
    }

    public function to_user()
    {
        return $this->belongsTo(User::class,'cthread_to_user_id','_id');
    }

    public function chat_media(){
        return $this->hasMany(ChatMedia::class,'cthread_id','_id');
    }
}
