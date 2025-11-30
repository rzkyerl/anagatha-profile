<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRecruiterOrAdmin
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that only users with role 'recruiter' or 'admin' can access the route.
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
        
        // Only allow users with role 'recruiter' or 'admin'
        if (!in_array($user->role, ['recruiter', 'admin'])) {
            // If user is regular user (employee/jobseeker), redirect to home
            if ($user->role === 'user') {
                return redirect()->route('home')
                    ->with('status', 'You do not have permission to access the admin panel.')
                    ->with('toast_type', 'warning');
            }
            
            // For any other role, logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login')
                ->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}

