<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiTokenTeletch
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Authorization') !== env('CUSTOM_API_TOKEN_TELETECH')) {
            return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
        }

        return $next($request);
    }
}
