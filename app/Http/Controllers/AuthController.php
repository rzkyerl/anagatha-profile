<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Notifications\CustomResetPassword;

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

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('verification.notice')
                    ->with('status', 'Please verify your email address before logging in. A verification link has been sent to your email.')
                    ->with('toast_type', 'warning');
            }

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
                    'industry' => 'required|in:Technology,Healthcare,Finance,Education,Manufacturing,Retail,Real Estate,Hospitality,Transportation & Logistics,Energy,Telecommunications,Media & Entertainment,Consulting,Legal,Construction,Agriculture,Food & Beverage,Automotive,Aerospace,Pharmaceuticals,Other',
                    'industry_other' => 'required_if:industry,Other|nullable|string|max:255',
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
                    'industry.required' => 'Industry is required.',
                    'industry.in' => 'Please select a valid industry.',
                    'industry_other.required_if' => 'Please enter your custom industry.',
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
                
                // Handle 'Other' selection for industry
                $validated['industry_other'] = $validated['industry'] === 'Other' ? $validated['industry_other'] : null;

                $user = \App\Models\User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                    'phone' => $validated['phone'],
                    'job_title' => $validated['job_title'],
                    'job_title_other' => $validated['job_title_other'],
                    'company_name' => $validated['company_name'],
                    'industry' => $validated['industry'],
                    'industry_other' => $validated['industry_other'],
                    'role' => 'recruiter',
                ]);

                // Create company record in companies table
                Company::create([
                    'user_id' => $user->id,
                    'name' => $validated['company_name'],
                    'logo' => null, // Logo can be uploaded later
                    'industry' => $validated['industry'],
                    'industry_other' => $validated['industry_other'],
                    'location' => null, // Location can be set later in company profile
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
                ]);
            }

            // Clear the role from session
            $request->session()->forget('register_role');

            // Login user so they can access verification page
            Auth::login($user);

            // Send email verification notification
            try {
                $user->sendEmailVerificationNotification();
                $emailSent = true;
            } catch (\Exception $emailException) {
                // Log email error but don't fail registration
                Log::warning('Email verification failed to send: ' . $emailException->getMessage(), [
                    'email' => $user->email,
                    'user_id' => $user->id,
                ]);
                $emailSent = false;
            }

            // Redirect to email verification notice page
            if ($emailSent) {
                return redirect()->route('verification.notice')
                    ->with('status', 'Registration successful! Please verify your email address to continue.')
                    ->with('toast_type', 'success');
            } else {
                return redirect()->route('verification.notice')
                    ->with('status', 'Registration successful! However, we encountered an issue sending the verification email. Please use the "Resend Verification Email" button below.')
                    ->with('toast_type', 'warning');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be shown via toast in the view
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            $errorMessage = $e->getMessage();
            Log::error('Registration database error: ' . $errorMessage, [
                'email' => $request->email,
                'role' => $role,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check for specific database errors
            $userMessage = 'Registration failed due to a database error. ';
            if (str_contains($errorMessage, "Unknown column 'industry'")) {
                $userMessage .= 'Please run database migration: php artisan migrate';
            } elseif (str_contains($errorMessage, "Data too long for column 'industry'")) {
                $userMessage .= 'Industry value is too long. Please select a valid industry.';
            } elseif (str_contains($errorMessage, "Unknown column")) {
                $userMessage .= 'Database schema is missing required columns. Please run: php artisan migrate';
            } else {
                $userMessage .= 'Please check all fields and try again. If the problem persists, please contact support.';
            }
            
            return redirect()->back()
                ->with('status', $userMessage)
                ->with('toast_type', 'error')
                ->withInput();
        } catch (\Exception $e) {
            // Handle any other errors with detailed logging
            Log::error('Registration error: ' . $e->getMessage(), [
                'email' => $request->email ?? 'unknown',
                'role' => $role,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Show user-friendly error message
            $errorMessage = 'Registration failed. ';
            if (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage .= 'There was a problem saving your data. Please check all fields and try again.';
            } else {
                $errorMessage .= 'Please check your input and try again. If the problem persists, please contact support.';
            }
            
            return redirect()->back()
                ->with('status', $errorMessage)
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
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     * Only verified users can reset their password.
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Check if user exists and is verified
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Don't reveal if email exists or not (security best practice)
            return back()->with('status', 'If that email address exists in our system, we will send a password reset link.')
                ->with('toast_type', 'success');
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return back()->with('status', 'Please verify your email address before resetting your password. Check your email for the verification link.')
                ->with('toast_type', 'warning')
                ->withInput();
        }

        // Generate password reset token
        $token = Str::random(64);
        
        // Store token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send custom reset password notification
        try {
            $user->notify(new CustomResetPassword($token));
            
            return back()->with('status', 'Password reset link has been sent to your email address.')
                ->with('toast_type', 'success');
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage(), [
                'email' => $request->email,
            ]);

            return back()->with('status', 'Failed to send password reset email. Please try again later.')
                ->with('toast_type', 'error')
                ->withInput();
        }
    }

    /**
     * Show the password reset form.
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'token.required' => 'Reset token is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('status', 'Invalid email address.')
                ->with('toast_type', 'error')
                ->withInput();
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return back()->with('status', 'Please verify your email address before resetting your password.')
                ->with('toast_type', 'warning')
                ->withInput();
        }

        // Verify token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->with('status', 'Invalid or expired reset token. Please request a new password reset link.')
                ->with('toast_type', 'error')
                ->withInput();
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('status', 'This password reset link has expired. Please request a new one.')
                ->with('toast_type', 'error')
                ->withInput();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Auto-login user
        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'admin') {
            $redirectRoute = 'admin.dashboard';
        } elseif ($user->role === 'recruiter') {
            $redirectRoute = 'recruiter.dashboard';
        } else {
            $redirectRoute = 'home';
        }

        return redirect()->route($redirectRoute)
            ->with('status', 'Password reset successfully! You have been logged in.')
            ->with('toast_type', 'success');
    }
}

