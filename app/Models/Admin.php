<?php

namespace App\Models;

use App\Notifications\AdminResetPassword;
use App\Traits\LockableTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;


use DesignMyNight\Mongodb\Auth\User as Authenticatable;
use Maklad\Permission\Models\Role;
use Maklad\Permission\Traits\HasRoles;


class Admin extends Authenticatable
{
    use Notifiable, HasRoles, Authorizable, LockableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password',
        'lockout_time',
        'country_id',
        'state_id',
        'city_id',
        'neighborhoods',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $appends = ['full_image'];

    public function getfullImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->profile_image){
            $image_name = $this->profile_image;
        }
        return checkProfile('profile/'.$image_name);

    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }


    public function user_roles()
    {
        return $this->belongsTo(Role::class,'role_id','_id');
    }
}
