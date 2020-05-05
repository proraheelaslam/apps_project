<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Neighborhood extends Model
{
    //
    const CREATED_AT = 'neighborhood_created_at';
    const UPDATED_AT = 'neighborhood_updated_at';

    protected $table = "neighborhoods";

    protected $fillable = [
        'neighborhood_name',
        'neighborhood_address',
        'neighborhood_area',
        'neighborhood_total_users',
        'created_by',
        'verified_by_admin',
        'neighborhood_sef_url',
        'nstatus_id',
        'country_id',
        'state_id',
        'city_id'

    ];

    protected $hidden = [
      'neighborhood_created_at',
      'neighborhood_updated_at'
    ];

    public function neighbourhoodStatus()
    {
        return $this->belongsTo(NeighborhoodStatus::class,'nstatus_id','_id');
    }

    public function users()
    {
        return $this->hasMany(UserNeighborhood::class,'neighborhood_id','_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','_id');
    }


    public function posts() {

        return $this->hasMany(UserPost::class,'neighborhood_id','_id');
    }


}
