<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminDomain
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that admin routes can only be accessed from the admin domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $adminDomain = env('ADMIN_DOMAIN', 'anagataexecutive.com');
        
        // Allow both with and without www
        $allowedHosts = [
            $adminDomain,
            'www.' . $adminDomain,
        ];
        
        // If not on admin domain, redirect to admin domain
        if (!in_array($host, $allowedHosts)) {
            $mainDomain = env('APP_DOMAIN', 'anagataexecutive.co.id');
            
            // If accessing from main domain, redirect to admin domain
            if (in_array($host, [$mainDomain, 'www.' . $mainDomain])) {
                $scheme = $request->getScheme();
                $path = $request->path();
                $adminUrl = $scheme . '://' . $adminDomain . ($path !== '/' ? '/' . $path : '');
                return redirect($adminUrl, 301);
            }
            
            // For any other domain, abort
            abort(403, 'Access denied. This route is only available on the admin domain.');
        }

        return $next($request);
    }
}

