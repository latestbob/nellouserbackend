<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\TransactionLog;
use App\Traits\Paystack;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    use Paystack;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Package::with(['benefits'])->get();
    }


    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'payment_reference' => 'required|string'
        ]);

        $user = Auth::user();

        $plan = Package::find($request->package_id);
        $check = $this->verify($data['payment_reference'], $plan->price);
        if ($check['status']) {
            $ref = random_int(10000000, 99999999);
            $tranx = TransactionLog::create([
                'gateway_reference' => $data['payment_reference'],
                'system_reference' => "$ref",
                'reason' => 'Subscription payment',
                'amount' => $plan->price,
                'email' => $user->email
            ]);

            $sub = Subscription::create([
                'user_id' => $user->id,
                'reference' => "$ref",
                'package_id' => $request->package_id,
                'transaction_id' => $tranx->id,
                'start_date' => Carbon::today(),
                'expiration_date' => Carbon::today()->addMonth(),
            ]);

            // Paystack transaction charge
            $charges = $plan->price * 0.015;
            if ($plan->price > 2500) {
                $charges = $charges + 100;
            }
            if ($charges > 2000) {
                $charges = 2000;
            }

            return [
                'subscription' => $sub,
                'sub_amount' => $plan->price,
                'total' => $charges
            ];
        }

        return response(['errors' => [
            'payment_reference' => [
                $check['message']
            ]
        ]], 422);
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
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        //
    }
}
