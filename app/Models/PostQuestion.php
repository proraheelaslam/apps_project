<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class PostQuestion extends Model
{
    //
    protected $table = "post_questions";

    protected $fillable = [
        'upost_id',
        'pquestion_question'
    ];

    protected $hidden = ['created_at','updated_at'];


    public function answers()
    {
        return $this->hasMany(PostAnswer::class,'upost_id','_id');
    }

    public function post()
    {
        return $this->belongsTo(UserPost::class,'upost_id','_id');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($postQuestion) {
            foreach ($postQuestion->answers()->get() as $q) {
                $q->delete();
            }
        });

    }

}
