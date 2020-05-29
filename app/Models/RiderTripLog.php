<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderTripLog extends Model
{
    protected $fillable = ['rider_id', 'order_id', 'distance', 'time'];

    public function rider()
    {
        return $this->belongsTo('App\Models\User', 'rider_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
