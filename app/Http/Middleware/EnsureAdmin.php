<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that only users with role 'admin' can access the route.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $user = Auth::user();
        
        // Only allow users with role 'admin'
        if ($user->role !== 'admin') {
            // If user is recruiter, redirect to recruiter dashboard on main domain
            if ($user->role === 'recruiter') {
                // Redirect to recruiter dashboard on main domain
                $mainDomain = env('APP_DOMAIN', 'anagataexecutive.co.id');
                $scheme = $request->getScheme();
                $url = $scheme . '://' . $mainDomain . '/recruiter/dashboard';
                return redirect($url)
                    ->with('status', 'You do not have permission to access this page. Admin access required.')
                    ->with('toast_type', 'warning');
            }
            
            // If user is regular user, redirect to home
            if ($user->role === 'user') {
                return redirect()->route('home')
                    ->with('status', 'You do not have permission to access this page. Admin access required.')
                    ->with('toast_type', 'warning');
            }
            
            // For any other role, logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login')
                ->with('error', 'You do not have permission to access this page. Admin access required.');
        }

        return $next($request);
    }
}

