<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FitnessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'height_in_feet' => 'required|numeric',
            'height_in_inches' => 'required|numeric',
            'weight_in_kgs' => 'required|numeric',
            'weight_in_lbs' => 'required|numeric',
            'energy_unit' => 'required|in:calories,kilojoules',
            'normal_daily_activity' => 'required|in:sendentary,lightly_active,active,hyper_active',
            'weight_goal' => 'required|string',
            'sleep_goal' => 'required|string',
            'health_interests' => 'required|array',
            'health_interests.*' => 'required|string',
            'weekly_exercise_plan' => 'required|array',
            'weekly_exercise_plan.workouts_per_week' => 'required|integer',   
            'weekly_exercise_plan.minutes_per_workout' => 'required|integer'   
        ];
    }
}
