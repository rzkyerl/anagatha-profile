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
            // If user is recruiter or admin, redirect to admin dashboard
            if (in_array($user->role, ['recruiter', 'admin'])) {
                return redirect()->route('admin.dashboard');
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

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is admin or recruiter
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'recruiter'])) {
                Auth::logout();
                return redirect()->back()
                    ->with('error', 'You do not have permission to access the admin panel.')
                    ->withInput($request->only('email'));
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $user->first_name . '!');
        }

        return redirect()->back()
            ->with('error', 'These credentials do not match our records.')
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

