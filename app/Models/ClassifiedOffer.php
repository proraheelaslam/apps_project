<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ClassifiedOffer extends Model
{
    //
    protected $table = "classified_offers";

    protected $fillable = ['classified_id','user_id','coffer_name','coffer_price','coffer_comments'];

    protected $hidden = ['created_at','updated_at'];

    public function users(){
    	return $this->belongsTo(User::class, 'user_id','_id');
    }


}
