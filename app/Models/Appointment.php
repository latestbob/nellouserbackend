<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    
    protected $fillable = ['description','user_id','status','reason','app_date','app_time','app_location','source','session_id','ref_no','center_id'];
}
