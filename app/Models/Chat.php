<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Chat extends Model
{
    //
    protected $table = "chats";

    protected $fillable = [
        'chat_to_user_id',
        'chat_from_user_id',

    ];


    public function chat_from_user()
    {
        return $this->belongsTo(User::class,'chat_from_user_id','_id');
    }

    public function chat_to_user()
    {
        return $this->belongsTo(User::class,'chat_to_user_id','_id');
    }
}
