<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CronjobApiController extends Controller
{
    //
    public function sendBirthdayNotificaiton()
    {

        //$dob = "1994-02-22";
        // $user =  User::whereDate('user_date_of_birth',new MongoDate(strtotime))->get();
        // dd($user);
        //return Carbon::today()->toDateString();
        //Carbon::parse($val->event_date)->format('F Y')

        /*if (Carbon::parse($dob)->isBirthday()) {
                return "happy birthday";
         }*/
        /*exit();
        foreach ($users as $user) { dump(Carbon::today()->diffInYears($user->user_date_of_birth));
            if (Carbon::today()->diffInYears($user->user_date_of_birth) > Carbon::now()->toDateString()) {
                dd("dd");
            }
        }*/

        // return $user;
    }
}
