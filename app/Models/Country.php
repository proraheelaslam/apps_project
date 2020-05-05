<?php

namespace App\models;

use Jenssegers\Mongodb\Eloquent\Model;

class Country extends Model
{
    protected $table = "countries";

    public function states(){
    	return $this->hasMany(State::class, 'country_id','country_id');
    }
}
