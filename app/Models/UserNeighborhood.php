<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class UserNeighborhood extends Model
{
    //
    protected $table = "user_neighborhood";

    protected $fillable = [
        'user_id',
        'neighborhood_id'
    ];

    public function neighborhood_detail()
    {
        return $this->belongsTo(Neighborhood::class,'neighborhood_id');
    }
    public function user_detail()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('user_detail',function ($q) {
           $q->whereHas('status',function ($q) {
                 $q->where('ustatus_name','approved');
           });
        });
    }


}
