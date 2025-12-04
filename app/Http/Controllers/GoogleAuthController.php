<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth provider.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Check if user exists by email (for users who registered with email/password)
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // If user exists but doesn't have google_id, update it
                    // Only allow if user role is 'user' (not recruiter or admin)
                    if ($user->role !== 'user') {
                        return redirect()->route('login')
                            ->with('status', 'Google authentication is only available for regular users.')
                            ->with('toast_type', 'error');
                    }

                    $user->google_id = $googleUser->getId();
                    // Update avatar if not set
                    if (!$user->avatar && $googleUser->getAvatar()) {
                        $user->avatar = $googleUser->getAvatar();
                    }
                    $user->save();
                } else {
                    // Create new user with role 'user'
                    $name = $googleUser->getName();
                    $nameParts = explode(' ', $name, 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : null;

                    $user = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => bcrypt(Str::random(32)), // Random password since using Google OAuth
                        'role' => 'user',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(), // Google emails are verified
                    ]);
                }
            } else {
                // User exists with google_id, verify role is 'user'
                if ($user->role !== 'user') {
                    return redirect()->route('login')
                        ->with('status', 'Google authentication is only available for regular users.')
                        ->with('toast_type', 'error');
                }

                // Update avatar if changed
                if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }
            }

            // Login the user
            Auth::login($user, true);

            // Redirect to home page for regular users
            return redirect()->route('home')
                ->with('status', 'Welcome back, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!')
                ->with('toast_type', 'success');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('status', 'Failed to authenticate with Google. Please try again.')
                ->with('toast_type', 'error');
        }
    }
}
