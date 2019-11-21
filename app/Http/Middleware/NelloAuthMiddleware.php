<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;

class NelloAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('api-key');
        $authorization = $request->header('authorization');
        $vendor = Vendor::where('api_key', $apiKey)->first();
        if (!empty($vendor)) {
            $token = $this->getVendorToken($vendor);
            if ($token === $authorization) {
                return $next($request);
            }    
        }
        return response(['error' => 'forbidden'], 401);
    }
}
