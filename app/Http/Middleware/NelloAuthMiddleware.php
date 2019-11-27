<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

//use App\Http\Controllers\GuzzleClient;

class NelloAuthMiddleware
{

    //use GuzzleClient;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $today = Carbon::today()->toDateString();
        $apiKey = $request->header('api-key');
        $authorization = $request->header('authorization');
        $vendor = Vendor::where('api_key', $apiKey)->first();
        $token = '';
        if (!empty($vendor)) {
            $request->request->add(['vendor_id' => $vendor->id]);
            $token = $vendor->api_key . $vendor->api_secret . $today;
            //$token = $this->getVendorToken($vendor);
            if (Hash::check($token, $authorization) /*$token === $authorization*/) {
                //return response(['kjhsaghjghahsgahgsjaghf']);
                return $next($request);
            }    
        }
        return response([
            'error' => 'forbidden', 
            'api_key' => $apiKey,
            'authorization' => $authorization,
            'token' => $token
        ], 401);
    }
}
