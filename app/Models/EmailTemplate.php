<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class EmailTemplate extends Model
{
    //
    protected $table = "email_templates";

    protected $fillable = [
      'name',
      'from',
      'subject',
      'content',
      'key',
    ];
}
