<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRecruiter
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that only users with role 'recruiter' can access the route.
     * Admin will be redirected to admin domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('status', 'Please login to access this page.')
                ->with('toast_type', 'warning');
        }

        $user = Auth::user();
        
        // Only allow users with role 'recruiter'
        if ($user->role !== 'recruiter') {
            // If user is admin, redirect to admin domain
            if ($user->role === 'admin') {
                $adminDomain = env('ADMIN_DOMAIN', 'admin.anagataexecutive.co.id');
                $scheme = $request->getScheme();
                $url = $scheme . '://' . $adminDomain . '/dashboard';
                return redirect($url)
                    ->with('status', 'Admin should access dashboard from admin domain.')
                    ->with('toast_type', 'info');
            }
            
            // If user is regular user, redirect to home
            if ($user->role === 'user') {
                return redirect()->route('home')
                    ->with('status', 'You do not have permission to access this page.')
                    ->with('toast_type', 'warning');
            }
            
            // For any other role, logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('status', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}

