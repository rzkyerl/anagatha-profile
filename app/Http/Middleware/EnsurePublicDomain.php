<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePublicDomain
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that public routes can only be accessed from the main domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $mainDomain = env('APP_DOMAIN', 'anagataexecutive.co.id');
        $adminDomain = env('ADMIN_DOMAIN', 'anagataexecutive.com');
        
        // Allow both with and without www
        $allowedHosts = [
            $mainDomain,
            'www.' . $mainDomain,
        ];
        
        // If accessing from admin domain, redirect to main domain
        if (in_array($host, [$adminDomain, 'www.' . $adminDomain])) {
            $scheme = $request->getScheme();
            $path = $request->path();
            $mainUrl = $scheme . '://' . $mainDomain . ($path !== '/' ? '/' . $path : '');
            return redirect($mainUrl, 301);
        }
        
        // If not on main domain and not admin domain, abort
        if (!in_array($host, $allowedHosts)) {
            abort(403, 'Access denied. This route is only available on the main domain.');
        }

        return $next($request);
    }
}

