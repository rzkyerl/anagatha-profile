<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that only users with role 'user' (employees/jobseekers) can access the route.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('status', 'Please login to access this page.')
                ->with('toast_type', 'info');
        }

        $user = Auth::user();
        
        // Only allow users with role 'user' (employees/jobseekers)
        if ($user->role !== 'user') {
            // If user is recruiter or admin, redirect to admin dashboard
            if (in_array($user->role, ['recruiter', 'admin'])) {
                return redirect()->route('admin.dashboard')
                    ->with('status', 'You do not have permission to access this page.')
                    ->with('toast_type', 'warning');
            }
            
            // For any other role, redirect to home
            return redirect()->route('landing')
                ->with('status', 'You do not have permission to access this page.')
                ->with('toast_type', 'warning');
        }

        return $next($request);
    }
}

