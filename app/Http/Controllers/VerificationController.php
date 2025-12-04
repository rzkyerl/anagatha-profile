<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     * This can be called even if user is not logged in (from email link).
     */
    public function verify(Request $request, $id, $hash)
    {
        // Find user by ID
        $user = User::findOrFail($id);

        // Verify the hash matches the user's email
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            // If user is logged in, redirect to dashboard
            if (Auth::check() && Auth::id() === $user->id) {
                return $this->redirectAfterVerification($user);
            }
            // If not logged in, login and redirect
            Auth::login($user);
            return $this->redirectAfterVerification($user);
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        // Login user if not already logged in
        if (!Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
        }

        return $this->redirectAfterVerification($user);
    }

    /**
     * Redirect user after successful verification based on their role.
     */
    protected function redirectAfterVerification($user)
    {
        // Redirect based on user role
        if ($user->role === 'admin') {
            $redirectRoute = 'admin.dashboard';
            $message = 'Email verified successfully! Welcome to your dashboard.';
        } elseif ($user->role === 'recruiter') {
            $redirectRoute = 'recruiter.dashboard';
            $message = 'Email verified successfully! Welcome to your dashboard.';
        } else {
            $redirectRoute = 'home';
            $message = 'Email verified successfully! Welcome back, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!';
        }

        return redirect()->route($redirectRoute)
            ->with('status', $message)
            ->with('toast_type', 'success');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect based on user role
            $user = $request->user();
            if ($user->role === 'admin') {
                $redirectRoute = 'admin.dashboard';
            } elseif ($user->role === 'recruiter') {
                $redirectRoute = 'recruiter.dashboard';
            } else {
                $redirectRoute = 'home';
            }

            return redirect()->route($redirectRoute)
                ->with('status', 'Your email is already verified.')
                ->with('toast_type', 'success');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            
            return back()->with('status', 'Verification link sent! Please check your email.')
                ->with('toast_type', 'success');
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return back()->with('status', 'Failed to send verification email. Please check your email configuration or try again later.')
                ->with('toast_type', 'error');
        }
    }
}

