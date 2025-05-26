<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Authorization') !== env('CUSTOM_API_TOKEN')) {
            return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
        }

        return $next($request);
    }
}
