<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'uuid', 
        'name', 
        'address', 
        'email', 
        'phone', 
        'picture', 
        'password', 
        'location_id', 
        'is_pick_up_location'
    ];

    public function location() {
        return $this->belongsTo('App\Models\Location', 'location_id', 'id');
    }

    public function agents() {
        return $this->hasMany('App\Models\User', 'pharmacy_id', 'id');
    }
}
