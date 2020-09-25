<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    //protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id', 'token',
        'firstname','lastname','middlename','email','phone',
        'user_type','aos','cwork','password','picture','dob',
        'hwg','is_seen','ufield','height','weight','gender','source',
        'session_id','address','state','city','religion','sponsor',
        'uuid', 'local_saved', 'pharmacy_id', 'location_id', 'about'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['rating'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'central_saved' => 'boolean',
        'local_saved' => 'boolean'
    ];


    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function pharmacy() {
        return $this->belongsTo('App\Models\Pharmacies', 'pharmacy_id', 'id');
    }

    public function location() {
        return $this->belongsTo('App\Models\Locations', 'location_id', 'id');
    }

    public function encounters()
    {
        return $this->hasMany('App\Models\Encounter', 'user_uuid', 'uuid');
    }

    public function medications()
    {
        return $this->hasMany('App\Models\Medication', 'user_uuid', 'uuid');
    }


    public function vitals()
    {
        return $this->hasMany('App\Models\Vital', 'user_uuid', 'uuid');
    }

    public function procedures()
    {
        return $this->hasMany('App\Models\Procedure', 'user_uuid', 'uuid');
    }

    public function investigations()
    {
        return $this->hasMany('App\Models\Investigation', 'user_uuid', 'uuid');
    }

    public function invoices() {
        //return $this->hasMany('App\Models\Invoice');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\PaystackPayment', 'user_uuid', 'uuid');
    }


    public function getRatingAttribute()
    {
        $rating = DoctorRating::where('doctor_uuid', $this->uuid)->sum('rating');
        return $rating;
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
