<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth provider.
     */
    public function redirect()
    {
        // Validate OAuth configuration before redirecting
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        \Log::info('Google OAuth redirect initiated', [
            'has_client_id' => !empty($clientId),
            'has_client_secret' => !empty($clientSecret),
            'has_redirect_uri' => !empty($redirectUri),
            'client_id_prefix' => !empty($clientId) ? substr($clientId, 0, 20) . '...' : 'empty',
            'redirect_uri' => $redirectUri,
            'env' => config('app.env'),
        ]);

        if (empty($clientId) || empty($clientSecret) || empty($redirectUri)) {
            \Log::error('Google OAuth configuration missing', [
                'has_client_id' => !empty($clientId),
                'has_client_secret' => !empty($clientSecret),
                'has_redirect_uri' => !empty($redirectUri),
            ]);

            return redirect()->route('login')
                ->with('status', 'Google authentication is not properly configured. Please contact the administrator.')
                ->with('toast_type', 'error');
        }

        try {
            \Log::info('Attempting Google OAuth redirect', [
                'client_id_length' => strlen($clientId),
                'redirect_uri' => $redirectUri,
            ]);
            
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth redirect error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'client_id' => substr($clientId, 0, 20) . '...',
                'redirect_uri' => $redirectUri,
                'exception_class' => get_class($e),
            ]);

            return redirect()->route('login')
                ->with('status', 'Failed to initiate Google authentication. Please try again later.')
                ->with('toast_type', 'error');
        }
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function callback()
    {
        \Log::info('Google OAuth callback received', [
            'request_params' => request()->all(),
            'request_url' => request()->fullUrl(),
        ]);

        try {
            \Log::info('Attempting to get Google user from Socialite');
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google user retrieved successfully', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'has_avatar' => !empty($googleUser->getAvatar()),
            ]);

            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->getId())->first();
            \Log::info('User lookup by google_id', [
                'google_id' => $googleUser->getId(),
                'user_found' => !is_null($user),
            ]);

            if (!$user) {
                // Check if user exists by email (for users who registered with email/password)
                $user = User::where('email', $googleUser->getEmail())->first();
                \Log::info('User lookup by email', [
                    'email' => $googleUser->getEmail(),
                    'user_found' => !is_null($user),
                ]);

                if ($user) {
                    // If user exists but doesn't have google_id, update it
                    // Only allow if user role is 'user' (not recruiter or admin)
                    \Log::info('Existing user found, updating with google_id', [
                        'user_id' => $user->id,
                        'current_role' => $user->role,
                    ]);
                    
                    if ($user->role !== 'user') {
                        \Log::warning('Google OAuth attempted for non-user role', [
                            'user_id' => $user->id,
                            'role' => $user->role,
                        ]);
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
                    \Log::info('User updated with Google credentials', ['user_id' => $user->id]);
                } else {
                    // Create new user with role 'user'
                    $name = $googleUser->getName();
                    $nameParts = explode(' ', $name, 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : null;

                    \Log::info('Creating new user from Google OAuth', [
                        'email' => $googleUser->getEmail(),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ]);

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
                    \Log::info('New user created successfully', ['user_id' => $user->id]);
                }
            } else {
                // User exists with google_id, verify role is 'user'
                \Log::info('User found with existing google_id', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                ]);
                
                if ($user->role !== 'user') {
                    \Log::warning('Google OAuth attempted for non-user role', [
                        'user_id' => $user->id,
                        'role' => $user->role,
                    ]);
                    return redirect()->route('login')
                        ->with('status', 'Google authentication is only available for regular users.')
                        ->with('toast_type', 'error');
                }

                // Update avatar if changed
                if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                    \Log::info('Updating user avatar', ['user_id' => $user->id]);
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }
            }

            // Login the user
            \Log::info('Logging in user', ['user_id' => $user->id, 'email' => $user->email]);
            Auth::login($user, true);

            // Redirect to home page for regular users
            \Log::info('Google OAuth successful, redirecting to home', ['user_id' => $user->id]);
            return redirect()->route('home')
                ->with('status', 'Welcome back, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!')
                ->with('toast_type', 'success');

        } catch (InvalidStateException $e) {
            \Log::warning('Google OAuth InvalidStateException: ' . $e->getMessage(), [
                'request_params' => request()->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')
                ->with('status', 'Authentication session expired. Please try again.')
                ->with('toast_type', 'error');
        } catch (ClientException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null;
            
            \Log::error('Google OAuth HTTP Error', [
                'status_code' => $statusCode,
                'response' => $responseBody,
                'message' => $e->getMessage(),
                'request_url' => request()->fullUrl(),
                'request_params' => request()->all(),
                'client_id' => substr(config('services.google.client_id', ''), 0, 20) . '...',
                'redirect_uri' => config('services.google.redirect'),
            ]);

            // Check for specific OAuth errors
            if ($statusCode === 401 || $statusCode === 400) {
                $errorMessage = 'Google OAuth configuration error. Please verify that the Client ID and Redirect URI are correctly configured in Google Cloud Console.';
            } else {
                $errorMessage = 'Failed to authenticate with Google. Please try again.';
            }

            return redirect()->route('login')
                ->with('status', $errorMessage)
                ->with('toast_type', 'error');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'class' => get_class($e),
                'request_url' => request()->fullUrl(),
                'request_params' => request()->all(),
            ]);

            return redirect()->route('login')
                ->with('status', 'Failed to authenticate with Google. Please try again.')
                ->with('toast_type', 'error');
        }
    }
}
