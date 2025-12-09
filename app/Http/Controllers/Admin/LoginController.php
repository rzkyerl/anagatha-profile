<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // If user is admin, redirect to admin dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            // If user is recruiter, redirect to main domain recruiter dashboard
            if ($user->role === 'recruiter') {
                $mainDomain = env('APP_DOMAIN', 'anagataexecutive.co.id');
                $scheme = request()->getScheme();
                $url = $scheme . '://' . $mainDomain . '/recruiter/dashboard';
                return redirect($url);
            }
            // If user is regular user, redirect to home
            return redirect()->route('home')
                ->with('status', 'You do not have permission to access the admin panel.')
                ->with('toast_type', 'warning');
        }

        return view('admin.auth.auth-admin-login', [
            'title' => 'Admin Login'
        ]);
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Check if user exists first
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return redirect()->back()
                ->with('error', 'These credentials do not match our records.')
                ->withInput($request->only('email'));
        }

        // Check if user is admin (only admin can login via admin login page)
        if ($user->role !== 'admin') {
            return redirect()->back()
                ->with('error', 'You do not have permission to access the admin panel. Only admin users can login here.')
                ->withInput($request->only('email'));
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Admin doesn't need email verification, but if not verified, mark as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $user->first_name . '!');
        }

        return redirect()->back()
            ->with('error', 'These credentials do not match our records. Please check your email and password.')
            ->withInput($request->only('email'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // Get user role before logout
        $userRole = Auth::user()->role ?? null;
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect based on user role
        if ($userRole === 'recruiter') {
            return redirect()->route('landing')
                ->with('success', 'You have been logged out successfully.');
        }

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

