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
        
        // Fallback: generate redirect URI from route if not configured
        if (empty($redirectUri)) {
            $redirectUri = route('google.callback');
        }

        // Normalize redirect URI: ensure consistent hostname (localhost vs 127.0.0.1)
        // Google OAuth treats localhost and 127.0.0.1 as different domains
        $parsedUri = parse_url($redirectUri);
        if ($parsedUri && isset($parsedUri['host'])) {
            // If using 127.0.0.1, check if APP_URL uses localhost (or vice versa)
            $appUrl = config('app.url');
            $parsedAppUrl = parse_url($appUrl);
            
            if ($parsedAppUrl && isset($parsedAppUrl['host'])) {
                // If APP_URL uses localhost but redirect URI uses 127.0.0.1 (or vice versa), normalize
                if (($parsedAppUrl['host'] === 'localhost' && $parsedUri['host'] === '127.0.0.1') ||
                    ($parsedAppUrl['host'] === '127.0.0.1' && $parsedUri['host'] === 'localhost')) {
                    // Use the host from APP_URL to maintain consistency
                    $redirectUri = str_replace($parsedUri['host'], $parsedAppUrl['host'], $redirectUri);
                    \Log::warning('Google OAuth redirect URI hostname normalized', [
                        'original' => $parsedUri['host'],
                        'normalized' => $parsedAppUrl['host'],
                        'redirect_uri' => $redirectUri,
                    ]);
                }
            }
        }

        // Normalize redirect URI (remove trailing slash if present, except for root)
        $redirectUri = rtrim($redirectUri, '/');
        if (parse_url($redirectUri, PHP_URL_PATH) === '') {
            $redirectUri .= '/';
        }

        \Log::info('Google OAuth redirect initiated', [
            'has_client_id' => !empty($clientId),
            'has_client_secret' => !empty($clientSecret),
            'has_redirect_uri' => !empty($redirectUri),
            'client_id_prefix' => !empty($clientId) ? substr($clientId, 0, 20) . '...' : 'empty',
            'redirect_uri' => $redirectUri,
            'app_url' => config('app.url'),
            'env' => config('app.env'),
        ]);

        if (empty($clientId) || empty($clientSecret)) {
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
            
            // Configure Socialite to use the correct redirect URI
            return Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->redirect();
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

        // Check for error in query parameters (from Google redirect)
        if (request()->has('error')) {
            $error = request()->get('error');
            $errorDescription = request()->get('error_description', 'Unknown error');
            $clientId = config('services.google.client_id');
            $redirectUri = config('services.google.redirect') ?: route('google.callback');
            
            \Log::error('Google OAuth error in callback', [
                'error' => $error,
                'error_description' => $errorDescription,
                'request_url' => request()->fullUrl(),
                'client_id_prefix' => !empty($clientId) ? substr($clientId, 0, 20) . '...' : 'empty',
                'redirect_uri' => $redirectUri,
            ]);

            $errorMessages = [
                'invalid_client' => 'Google OAuth Client ID tidak valid atau tidak ditemukan. ' .
                    'Pastikan: (1) Client ID dan Client Secret sudah benar di file .env, ' .
                    '(2) Redirect URI di .env (' . $redirectUri . ') sudah ditambahkan di Google Cloud Console, ' .
                    '(3) OAuth client belum dihapus atau dinonaktifkan di Google Cloud Console.',
                'access_denied' => 'Akses ditolak oleh pengguna. Silakan coba lagi.',
                'invalid_request' => 'Permintaan OAuth tidak valid. Pastikan konfigurasi sudah benar. ' .
                    'Periksa bahwa Redirect URI (' . $redirectUri . ') sudah dikonfigurasi dengan benar.',
                'redirect_uri_mismatch' => 'Redirect URI tidak cocok. Pastikan Redirect URI di .env (' . 
                    $redirectUri . ') sama persis dengan yang dikonfigurasi di Google Cloud Console.',
            ];

            $message = $errorMessages[$error] ?? 'Terjadi kesalahan saat autentikasi dengan Google: ' . $errorDescription;

            return redirect()->route('login')
                ->with('status', $message)
                ->with('toast_type', 'error');
        }

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
                $redirectUri = config('services.google.redirect') ?: route('google.callback');
                $errorMessage = 'Google OAuth configuration error. ' .
                    'Pastikan: (1) Client ID dan Client Secret sudah benar di file .env, ' .
                    '(2) Redirect URI (' . $redirectUri . ') sudah ditambahkan di Google Cloud Console, ' .
                    '(3) OAuth consent screen sudah dikonfigurasi dengan benar.';
            } else {
                $errorMessage = 'Failed to authenticate with Google. Please try again.';
            }

            return redirect()->route('login')
                ->with('status', $errorMessage)
                ->with('toast_type', 'error');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database connection errors
            $errorMessage = $e->getMessage();
            \Log::error('Google OAuth Database Error: ' . $errorMessage, [
                'trace' => $e->getTraceAsString(),
                'class' => get_class($e),
                'request_url' => request()->fullUrl(),
                'request_params' => request()->all(),
            ]);

            // Check if it's a connection error
            if (str_contains($errorMessage, 'No connection could be made') || 
                str_contains($errorMessage, 'Connection refused') ||
                str_contains($errorMessage, 'SQLSTATE[HY000]')) {
                $errorMessage = 'Database connection error. Please ensure MySQL is running and database configuration is correct. ' .
                    'Check your .env file for DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD settings.';
            } else {
                $errorMessage = 'Database error occurred during authentication. Please contact the administrator.';
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
