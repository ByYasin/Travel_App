<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($response instanceof Response && !$request->expectsJson()) {
            $response->headers->set('X-Robots-Tag', 'index, follow');
            
            $response->headers->set('Content-Security-Policy', "default-src 'self' *.googleapis.com *.gstatic.com;");
            
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            
            $response->headers->set('Cache-Control', 'public, max-age=3600');
        }
        
        return $response;
    }
}
