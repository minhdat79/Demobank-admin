<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $expected = (string) config('app.api_token', env('API_TOKEN', ''));
        if ($expected !== '' && $request->bearerToken() !== $expected) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
