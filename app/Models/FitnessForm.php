<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitnessForm extends Model
{
    protected $fillable = [
        'height_in_feet',
        'height_in_inches',
        'weight_in_kgs',
        'weight_in_lbs',
        'energy_unit',
        'normal_daily_activity',
        'weight_goal',
        'sleep_goal',
        'health_interests',
        'weekly_exercise_plan',
        'user_id'
    ];

    protected $casts = [
        'health_interests' => 'array',
        'weekly_exercise_plan' => 'array'
    ];
}
