<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $fullUrl = $request->fullUrl();
        
        $data = Cache::get($fullUrl);
        if (!empty($data)) {
            return response($data);
        }
        //return response(['fullUrl' => $fullUrl]);
        return $next($request);
    }
}
