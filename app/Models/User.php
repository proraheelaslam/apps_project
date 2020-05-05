<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends \Jenssegers\Mongodb\Eloquent\Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users";

    protected $fillable = [
        'user_fname',
        'user_lname',
        'user_email',
        'user_password',
        'gender_id',
        'user_address',
        'user_address_type',
        'user_address_document',
        'user_address_latitude',
        'user_address_longitude',
        'user_address_verify_code',
        'user_is_address_verified',
        'user_sef_url',
        'ustatus_id',
        'user_last_login',
        'user_ip_address',
        'user_image',
        'user_phone',
        'user_date_of_birth',
        'remember_token'
    ];

    //protected $dates = ['user_date_of_birth'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token','user_created_at','user_updated_at','created_at','updated_at',
        'gender_id','ustatus_id','neighborhoods'
    ];

    protected $appends = ['full_name','full_image','user_address_image'];

    public function getfullImageAttribute()
    {
        $image_name = 'no_image.png';
        if($this->attributes['user_image']){
            $image_name = $this->attributes['user_image'];
        }
        return checkImage('users/'.$image_name);

    }

    public function getEmailAttribute() {
        return $this->attributes['user_email'];
    }

    public function setEmailAttribute($value)
    {
        return $this->attributes['user_email']= $value;
    }

    public function getUserAddressImageAttribute()
    {
        $address_image_name = 'no_image.png';
        if($this->user_address_document){
            $address_image_name = $this->user_address_document;
        }
        return checkAddressImage('addresses/'.$address_image_name);
    }
    public function getAuthPassword() {
        return $this->user_password;
    }
    public function getFullNameAttribute() {
        return ucfirst($this->user_fname) . ' ' . ucfirst($this->user_lname);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class,'gender_id','_id');
    }

    public function status()
    {
        return $this->belongsTo(UserStatus::class,'ustatus_id','_id');
    }

    public function neighborhoods()
    {
        return $this->hasMany(UserNeighborhood::class,'user_id','_id');
    }

    public function answers()
    {
        return $this->hasMany(PostAnswer::class,'user_id','_id');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('ustatus_name','approved');
        });
    }

    // public function scopeBirthdays($query)
    // {
    //     return $query->where(DB::raw('DAY(dob)'), '=', 22)
    //         ->where(DB::raw('MONTH(dob)'), '=', 02);
    // }
}