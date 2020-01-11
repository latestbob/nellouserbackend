<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    protected $fillable = ['rating', 'user_uuid', 'doctor_uuid'];
}
