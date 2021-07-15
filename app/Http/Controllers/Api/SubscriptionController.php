<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorServiceRequest;
use App\Http\Requests\FitnessRequest;
use App\Models\DoctorServiceForm;
use App\Models\FitnessForm;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user->load([
            'fitnessSubscription',
            'doctorSubscription'
        ]);

        return [
            'fitness' => $user->fitnessSubscription,
            'doctor' => $user->doctorSubscription
        ];
    }

    public function doctorSubscribe(DoctorServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        DoctorServiceForm::create($data);

        return ['msg' => 'success'];
    }

    public function fitnessSubscribe(FitnessRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        FitnessForm::create($data);

        return ['msg' => 'success'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
