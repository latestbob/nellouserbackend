<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    protected $fillable = ['session_id','drug','test','diagnosis','note','status','encounter_date','bms', 'uuid', 'user_uuid'];
}
