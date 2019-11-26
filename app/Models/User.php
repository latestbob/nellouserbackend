<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','middlename','email','phone', 'user_type','aos','cwork','password','picture','dob','hwg','is_seen','ufield','height','weight','gender','source','session_id','address','state','city','day','month','year','eclinic_patient_id','eclinic_upi','religion','sponsor', 'uuid'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setUuidAttribute($value)
    {
        if (empty($value))
        $this->attributes['uuid'] = Str::uuid()->string;
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function encounters()
    {
        return $this->hasMany('App\Models\Encounter', 'uuid', 'user_uuid');
    }

    public function medications()
    {
        return $this->hasMany('App\Models\Medication', 'uuid', 'user_uuid');
    }

    public function vitals()
    {
        return $this->hasMany('App\Models\Vital', 'uuid', 'user_uuid');
    }

    public function procedures()
    {
        return $this->hasMany('App\Models\Procedure', 'uuid', 'user_uuid');
    }

    public function investigations()
    {
        return $this->hasMany('App\Models\Investigation', 'uuid', 'user_uuid');
    }

    public function invoices() {
        //return $this->hasMany('App\Models\Invoice');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\PaystackPayment', 'uuid', 'user_uuid');
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
