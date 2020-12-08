<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'  => config('cors.origin'),
            'Access-Control-Allow-Methods' => config('cors.methods'),
            'Access-Control-Allow-Headers' => config('cors.headers'),
        ];

        $response = $next($request);
        $response->headers->add($headers);

        return $response;
    }
}
