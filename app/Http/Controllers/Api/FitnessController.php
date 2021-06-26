<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FitnessRequest;
use App\Models\FitnessForm;
use Illuminate\Support\Facades\Auth;

class FitnessController extends Controller
{
    public function create(FitnessRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        FitnessForm::create($data);

        return ['msg' => 'success'];
    }
}
