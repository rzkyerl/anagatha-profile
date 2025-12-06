<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // If accessing admin login, redirect based on role
                if ($request->is('admin/login')) {
                    if ($user->role === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->role === 'recruiter') {
                        // Recruiter should stay on main domain
                        return redirect()->route('recruiter.dashboard');
                    } else {
                        // Regular users should not access admin login
                        return redirect()->route('home');
                    }
                }
                
                // For regular login page, redirect based on role
                if ($request->is('login')) {
                    if ($user->role === 'admin') {
                        $adminDomain = env('ADMIN_DOMAIN', 'admin.anagataexecutive.co.id');
                        $scheme = $request->getScheme();
                        $url = $scheme . '://' . $adminDomain . '/dashboard';
                        return redirect($url);
                    } elseif ($user->role === 'recruiter') {
                        // Recruiter should stay on main domain
                        return redirect()->route('recruiter.dashboard');
                    } else {
                        return redirect()->route('home');
                    }
                }
                
                // Default redirect based on role
                if ($user->role === 'admin') {
                    $adminDomain = env('ADMIN_DOMAIN', 'admin.anagataexecutive.co.id');
                    $scheme = $request->getScheme();
                    $url = $scheme . '://' . $adminDomain . '/dashboard';
                    return redirect($url);
                } elseif ($user->role === 'recruiter') {
                    // Recruiter should stay on main domain
                    return redirect()->route('recruiter.dashboard');
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
