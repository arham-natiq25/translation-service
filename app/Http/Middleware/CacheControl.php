<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply cache headers to the export endpoint
        if ($request->is('api/export')) {
            $response->header('Cache-Control', 'public, max-age=3600'); // 1 hour
            $response->header('Vary', 'Accept-Encoding, Authorization');
            $response->header('ETag', md5($response->getContent()));
        }

        return $response;
    }
}
