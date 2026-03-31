<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS filter in browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy to limit information leakage
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy to restrict browser features
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()'
        );

        // Content Security Policy (CSP) - strict policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
               "style-src 'self' 'unsafe-inline'; " .
               "img-src 'self' data: blob:; " .
               "font-src 'self'; " .
               "connect-src 'self'; " .
               "media-src 'self'; " .
               "object-src 'none'; " .
               "frame-ancestors 'self'; " .
               "base-uri 'self'; " .
               "form-action 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        // Strict Transport Security (HSTS) - only in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Cache control for sensitive pages
        if ($this->isSensitiveRoute($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }

    /**
     * Check if current route is sensitive (should not be cached).
     */
    private function isSensitiveRoute(Request $request): bool
    {
        $sensitivePrefixes = [
            'login',
            'register',
            'password',
            'pengguna',
            'pengaturan',
            'api/',
        ];

        $path = $request->path();

        foreach ($sensitivePrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
