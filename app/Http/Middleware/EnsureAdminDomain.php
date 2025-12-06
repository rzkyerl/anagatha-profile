<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminDomain
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that admin routes can only be accessed from the admin domain.
     * Recruiters are redirected to main domain as they should not access admin domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $adminDomain = env('ADMIN_DOMAIN', 'admin.anagataexecutive.co.id');
        $mainDomain = env('APP_DOMAIN', 'anagataexecutive.co.id');
        
        // Allow both with and without www
        $allowedHosts = [
            $adminDomain,
            'www.' . $adminDomain,
        ];
        
        // If recruiter is trying to access admin domain, redirect to main domain
        if (Auth::check() && Auth::user()->role === 'recruiter') {
            $scheme = $request->getScheme();
            $path = $request->path();
            
            // Map admin routes to main domain recruiter routes
            if (str_starts_with($path, 'recruiter/')) {
                // Keep the recruiter path
                $url = $scheme . '://' . $mainDomain . '/' . $path;
            } else {
                // Default to recruiter dashboard
                $url = $scheme . '://' . $mainDomain . '/recruiter/dashboard';
            }
            
            return redirect($url, 301);
        }
        
        // If not on admin domain, redirect to admin domain (only for admin users)
        if (!in_array($host, $allowedHosts)) {
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

