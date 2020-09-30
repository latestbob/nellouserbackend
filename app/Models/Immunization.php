<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    //
    protected $fillable = [
        'user_id',
        'user_uuid',
        'immunization_llin',
        'immunization_deworming',
        'immunization_vitamin_A',
        'immunization_status',
        'immunization_visits',
        'immunization_vaccines'
    ];
}
