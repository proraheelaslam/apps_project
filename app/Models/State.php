<?php

namespace App\models;

use Jenssegers\Mongodb\Eloquent\Model;

class State extends Model
{
	protected $table = "states";

	public function cities(){
    	return $this->hasMany(City::class, 'state_id','state_id');
    }
}
