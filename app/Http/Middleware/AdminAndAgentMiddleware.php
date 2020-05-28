<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAndAgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (($userType = $request->user()->user_type) == 'admin'
                || $userType == 'agent')) return $next($request);

        return response()->json([
            'status' => false,
            'message' => "You don't have access to this route"
        ]);
    }
}
