<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class BusinessReportReason extends Model
{
    //
    protected $table = 'business_report_reasons';

    protected $fillable = [
      'brreason_name'
    ];

    protected $hidden = ['created_at','updated'];


}
