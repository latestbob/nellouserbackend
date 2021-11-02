<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use Closure;

class PartnerMiddleware
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
        $apiKey = $request->header('authorization');
        $frags = explode(' ', $apiKey);
        if (count($frags) > 1) {
            $apiKey = $frags[1];

            $partner = Partner::where('api_key', $apiKey)->first();

            if (!empty($partner)) {
                $request->request->add(['partner_id' => $partner->id]);

                return $next($request);
            }
        }

        return response([
            'error' => 'Unauthorized',
            //'api_key' => $apiKey,
            //'authorization' => $authorization,
            //'token' => $token
        ], 401);
    }
}
