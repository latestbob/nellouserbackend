<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'uuid',
        'description',
        'user_uuid',
        'status',
        'reason',
        'date',
        'time',
        'location',
        'source',
        'session_id',
        'ref_no',
        'center_uuid',
        'doctor_id',
        'type'
    ];

    public function center()
    {
        return $this->hasOne('App\Models\HealthCenter', 'uuid', 'center_uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }


    public function doctor()
    {
        return $this->belongsTo('App\Models\User', 'doctor_id');
    }

    

}
