<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers for Firejob Platform
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', $this->getContentSecurityPolicy());
        
        // HTTPS Enforcement
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Additional Security Headers for Firejob Marketplace Platform
        $response->headers->set('Permissions-Policy', $this->getPermissionsPolicy());
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        
        return $response;
    }

    /**
     * Get Content Security Policy header value.
     */
    private function getContentSecurityPolicy(): string
    {
        $nonce = base64_encode(random_bytes(16));
        app()->instance('csp_nonce', $nonce);

        return "default-src 'self'; " .
               "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval'; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "connect-src 'self'; " .
               "media-src 'self' blob:; " .
               "object-src 'none'; " .
               "child-src 'self'; " .
               "worker-src 'self'; " .
               "frame-ancestors 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self';";
    }

    /**
     * Get Permissions Policy header value.
     */
    private function getPermissionsPolicy(): string
    {
        return 'camera=(), microphone=(), geolocation=(), interest-cohort=(), payment=()';
    }
}
