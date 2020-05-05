<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class PostAnswer extends Model
{
    //
    protected $table = "post_answers";

    protected $fillable = [
      'user_id',
      'upost_id',
      'pquestion_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','_id');
    }

    public function questions()
    {
        return $this->belongsTo(PostQuestion::class,'pquestion_id','_id');
    }
}
