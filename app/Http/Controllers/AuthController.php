<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // If user is already authenticated, redirect based on role
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'recruiter') {
                return redirect()->route('recruiter.dashboard');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user role
            if ($user->role === 'admin') {
                $redirectRoute = 'admin.dashboard';
            } elseif ($user->role === 'recruiter') {
                $redirectRoute = 'recruiter.dashboard';
            } else {
                $redirectRoute = 'home';
            }

            return redirect()->intended(route($redirectRoute))
                ->with('status', 'Welcome back, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!')
                ->with('toast_type', 'success');
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Show the role selection form.
     */
    public function showRegisterRoleForm()
    {
        return view('auth.register-role');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm(Request $request)
    {
        $role = $request->get('role', session('register_role', 'employee'));
        
        // If no role is set, redirect to role selection
        if (!$role || !in_array($role, ['employee', 'recruiter'])) {
            return redirect()->route('register.role');
        }

        // Store role in session for form submission
        session(['register_role' => $role]);

        if ($role === 'recruiter') {
            return view('auth.register-recruiter');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        try {
            $role = session('register_role', 'employee');
            
            if ($role === 'recruiter') {
                // Recruiter registration validation
                $validated = $request->validate([
                    'full_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'phone' => 'required|string|max:20',
                    'job_title' => 'required|in:HR Manager,HR Business Partner,Talent Acquisition Specialist,Recruitment Manager,HR Director,HR Coordinator,Recruiter,Senior Recruiter,HR Generalist,People Operations Manager,Other',
                    'job_title_other' => 'required_if:job_title,Other|nullable|string|max:255',
                    'company_name' => 'required|string|max:255',
                    'password' => 'required|string|min:8|confirmed',
                ], [
                    'full_name.required' => 'Full name is required.',
                    'email.required' => 'Work email is required.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.unique' => 'This email is already registered.',
                    'phone.required' => 'Phone / WhatsApp is required.',
                    'job_title.required' => 'Job Title / Position is required.',
                    'job_title.in' => 'Please select a valid job title.',
                    'job_title_other.required_if' => 'Please enter your custom job title.',
                    'company_name.required' => 'Company Name is required.',
                    'password.required' => 'Password is required.',
                    'password.min' => 'Password must be at least 8 characters.',
                    'password.confirmed' => 'Password confirmation does not match.',
                ]);

                // Split full name into first_name and last_name
                $nameParts = explode(' ', $validated['full_name'], 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : null;

                // Handle 'Other' selection for job_title
                $validated['job_title_other'] = $validated['job_title'] === 'Other' ? $validated['job_title_other'] : null;

                $user = \App\Models\User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                    'phone' => $validated['phone'],
                    'job_title' => $validated['job_title'],
                    'job_title_other' => $validated['job_title_other'],
                    'company_name' => $validated['company_name'],
                    'role' => 'recruiter',
                    'email_verified_at' => now(),
                ]);
            } else {
                // Employee registration validation
                $validated = $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'nullable|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8|confirmed',
                ], [
                    'first_name.required' => 'First name is required.',
                    'email.required' => 'Email is required.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.unique' => 'This email is already registered.',
                    'password.required' => 'Password is required.',
                    'password.min' => 'Password must be at least 8 characters.',
                    'password.confirmed' => 'Password confirmation does not match.',
                ]);

                $user = \App\Models\User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'] ?? null,
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
            }

            // Clear the role from session
            $request->session()->forget('register_role');

            // Don't auto-login user after registration
            // User needs to login manually after registration

            // Redirect to login page with success message
            return redirect()->route('login')
                ->with('status', 'Registration successful! Please login to continue.')
                ->with('toast_type', 'success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be shown via toast in the view
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Handle any other errors
            return redirect()->back()
                ->with('status', 'Registration failed. Please try again later.')
                ->with('toast_type', 'error')
                ->withInput();
        }
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the forgot password form (display only, no functionality).
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
}

