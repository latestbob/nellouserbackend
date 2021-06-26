<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorServiceForm extends Model
{
    protected $fillable = [
        'diagnosis',
        'medication',
        'allergies',
        'height_in_feet',
        'height_in_inches',
        'weight_in_kgs',
        'weight_in_lbs',
        'user_id'
    ];

    protected $casts = [
        'diagnosis' => 'array',
        'medication' => 'array',
        'allergies' => 'array',
    ];
}
