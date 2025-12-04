<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GitHubAuthController extends Controller
{
    /**
     * Redirect user to GitHub OAuth provider.
     */
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle callback from GitHub OAuth.
     */
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Check if user exists by github_id
            $user = User::where('github_id', $githubUser->getId())->first();

            if (!$user) {
                // Check if user exists by email (for users who registered with email/password)
                // Note: GitHub email might be null if user hasn't made it public
                $email = $githubUser->getEmail();
                
                if ($email) {
                    $user = User::where('email', $email)->first();
                }

                if ($user) {
                    // If user exists but doesn't have github_id, update it
                    // Only allow if user role is 'user' (not recruiter or admin)
                    if ($user->role !== 'user') {
                        return redirect()->route('login')
                            ->with('status', 'GitHub authentication is only available for regular users.')
                            ->with('toast_type', 'error');
                    }

                    $user->github_id = $githubUser->getId();
                    // Update github username if not set
                    if (!$user->github && $githubUser->getNickname()) {
                        $user->github = $githubUser->getNickname();
                    }
                    // Update avatar if not set
                    if (!$user->avatar && $githubUser->getAvatar()) {
                        $user->avatar = $githubUser->getAvatar();
                    }
                    $user->save();
                } else {
                    // Create new user with role 'user'
                    // GitHub might not provide email, so we need to handle that
                    if (!$email) {
                        return redirect()->route('login')
                            ->with('status', 'GitHub email is required. Please make your email public in GitHub settings or use another login method.')
                            ->with('toast_type', 'error');
                    }

                    $name = $githubUser->getName();
                    // If name is not provided, use username
                    if (!$name) {
                        $name = $githubUser->getNickname();
                    }
                    
                    $nameParts = explode(' ', $name, 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : null;

                    $user = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'github_id' => $githubUser->getId(),
                        'password' => bcrypt(Str::random(32)), // Random password since using GitHub OAuth
                        'role' => 'user',
                        'github' => $githubUser->getNickname(),
                        'avatar' => $githubUser->getAvatar(),
                        'email_verified_at' => now(), // GitHub emails are verified
                    ]);
                }
            } else {
                // User exists with github_id, verify role is 'user'
                if ($user->role !== 'user') {
                    return redirect()->route('login')
                        ->with('status', 'GitHub authentication is only available for regular users.')
                        ->with('toast_type', 'error');
                }

                // Update github username if changed
                if ($githubUser->getNickname() && $user->github !== $githubUser->getNickname()) {
                    $user->github = $githubUser->getNickname();
                }
                // Update avatar if changed
                if ($githubUser->getAvatar() && $user->avatar !== $githubUser->getAvatar()) {
                    $user->avatar = $githubUser->getAvatar();
                }
                $user->save();
            }

            // Login the user
            Auth::login($user, true);

            // Redirect to home page for regular users
            return redirect()->route('home')
                ->with('status', 'Welcome back, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!')
                ->with('toast_type', 'success');

        } catch (\Exception $e) {
            \Log::error('GitHub OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('status', 'Failed to authenticate with GitHub. Please try again.')
                ->with('toast_type', 'error');
        }
    }
}
