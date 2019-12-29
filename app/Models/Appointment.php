<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['description','user_uuid','status','reason','date','time','location','source','session_id','ref_no','center_uuid'];

    public function center() {
        return $this->hasOne('App\Models\HealthCenter', 'center_uuid', 'uuid');
    }
}
