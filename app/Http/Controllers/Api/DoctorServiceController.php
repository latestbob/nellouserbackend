<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorServiceRequest;
use App\Models\DoctorServiceForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorServiceController extends Controller
{
    public function create(DoctorServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        DoctorServiceForm::create($data);

        return ['msg' => 'success'];
    }
}
