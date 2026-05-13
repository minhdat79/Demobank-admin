<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $given = $request->bearerToken();
        $token = (string) config('app.api_token', env('API_TOKEN'));

        if (!$token || $given !== $token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
