<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = ['sitting_bp','temperature','weight','height','respiration_rate','pulse','session_id','vitals_date', 'uuid', 'user_uuid'];  

}
