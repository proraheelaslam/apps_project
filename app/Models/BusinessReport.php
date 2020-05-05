<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class BusinessReport extends Model
{
    //
    protected $table = 'business_reports';

    protected $fillable = [
      'business_id',
      'reported_by',
      'brreason_id',
      'breport_comment',
    ];

    protected $hidden = ['created_at','updated'];

    public function business_report_reasons(){
    	return $this->belongsTo(BusinessReportReason::class,'brreason_id','_id');
    }
    public function reported_by_user(){
    	return $this->belongsTo(User::class,'reported_by','_id');
    }
}
