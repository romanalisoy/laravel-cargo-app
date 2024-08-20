<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-TOKEN') !== config('auth.api_key')) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You need to provide a valid API key to access this resource'
            ], 401);
        }
        return $next($request);
    }
}
