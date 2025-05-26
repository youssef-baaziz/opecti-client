<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $secretKey = env('SIGNATURE_SECRET');
        if (empty($secret)) {
            return response()->json(['error' => 'Signature secret not configured'], 500);
        }
        $signature = $request->header('x-signature');
        $payload = json_encode($request->all());
        $expectedSignature = hash_hmac('sha256', $payload, $secretKey);
        echo "Generated Signature: " . $signature;
        echo "Generated expectedSignature: " . $expectedSignature;
        // if (!hash_equals($expectedSignature, $signature)) {
        //     abort(403, 'Invalid signature -------------------.');
        // }

        return $next($request);
    }
}
