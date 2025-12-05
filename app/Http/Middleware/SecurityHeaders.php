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
        // Generate a nonce for this request (for CSP inline scripts)
        $nonce = base64_encode(random_bytes(16));
        
        // Share nonce with views
        view()->share('cspNonce', $nonce);

        $response = $next($request);

        // Remove X-Powered-By header to hide server information
        $response->headers->remove('X-Powered-By');

        // Strict-Transport-Security (HSTS)
        // Forces browsers to use HTTPS for future connections
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Check if this is an admin or recruiter route - use more permissive CSP for admin dashboard
        $isAdminRoute = $request->is('admin/*') || $request->is('recruiter/*');
        
        // Content-Security-Policy (CSP) with nonce for inline scripts
        // Prevents XSS attacks by controlling which resources can be loaded
        // Using nonce instead of 'unsafe-inline' for better security
        // Removed 'unsafe-eval' as it's not needed and dangerous
        if ($isAdminRoute) {
            // Very permissive CSP for admin dashboard to allow all dashboard assets
            // This ensures CSS and JS from dashboard directory can load properly
            $csp = "default-src 'self' https: data: blob:; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; " .
                   "script-src-elem 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; " .
                   "style-src 'self' 'unsafe-inline' https: data: blob:; " .
                   "style-src-elem 'self' 'unsafe-inline' https: data: blob:; " .
                   "font-src 'self' https: data: blob:; " .
                   "img-src 'self' data: https: blob:; " .
                   "connect-src 'self' https:; " .
                   "frame-ancestors 'self'; " .
                   "base-uri 'self'; " .
                   "form-action 'self' http://127.0.0.1:* https://127.0.0.1:* http://localhost:* https://localhost:*;";
        } else {
            // Standard CSP for regular pages
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com https://www.googletagmanager.com https://www.google-analytics.com; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net; " .
                   "style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net; " .
                   "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
                   "img-src 'self' data: https: blob:; " .
                   "connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com; " .
                   "frame-ancestors 'self'; " .
                   "base-uri 'self'; " .
                   "form-action 'self' http://127.0.0.1:* https://127.0.0.1:* http://localhost:* https://localhost:*;";
        }
        $response->headers->set('Content-Security-Policy', $csp);

        // X-Frame-Options
        // Prevents clickjacking attacks by controlling if the page can be framed
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options
        // Prevents MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer-Policy
        // Controls how much referrer information is sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy (formerly Feature-Policy)
        // Controls which browser features and APIs can be used
        $permissionsPolicy = "geolocation=(), " .
                            "microphone=(), " .
                            "camera=(), " .
                            "payment=(), " .
                            "usb=(), " .
                            "magnetometer=(), " .
                            "gyroscope=(), " .
                            "speaker=(), " .
                            "vibrate=(), " .
                            "fullscreen=(self), " .
                            "sync-xhr=()";
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        return $response;
    }
}
