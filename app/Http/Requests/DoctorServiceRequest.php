<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorServiceRequest extends FormRequest
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
            'diagnosis' => 'required|array',
            'diagnosis.status' => 'required|in:yes,no',
            'diagnosis.detail' => 'nullable|string',
            'medication' => 'required|array',
            'medication.status' => 'required|in:yes,no',
            'medication.detail' => 'nullable|string',
            'allergies' => 'required|array',
            'allergies.status' => 'required|in:yes,no',
            'allergies.detail' => 'nullable|string',
        ];
    }
}
