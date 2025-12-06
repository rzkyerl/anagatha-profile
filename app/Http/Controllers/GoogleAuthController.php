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
    public function redirect(Request $request)
    {
        // Determine intended role from session or parameter
        // Check session for register_role (from registration page)
        $intendedRole = session('register_role', 'user');
        
        // If register_role is 'employee', it means regular user
        if ($intendedRole === 'employee') {
            $intendedRole = 'user';
        }
        
        // If register_role is 'recruiter', use 'recruiter'
        // Otherwise default to 'user' for login
        if (!in_array($intendedRole, ['user', 'recruiter'])) {
            $intendedRole = 'user';
        }
        
        // Store intended role in session for callback
        session(['google_oauth_intended_role' => $intendedRole]);
        
        \Log::info('Google OAuth redirect initiated', [
            'intended_role' => $intendedRole,
            'register_role_from_session' => session('register_role'),
        ]);
        
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

        \Log::info('Google OAuth redirect configuration', [
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
            // Get intended role from session
            $intendedRole = session('google_oauth_intended_role', 'user');
            // Clear the session after reading
            session()->forget('google_oauth_intended_role');
            
            // Normalize role
            if ($intendedRole === 'employee') {
                $intendedRole = 'user';
            }
            if (!in_array($intendedRole, ['user', 'recruiter'])) {
                $intendedRole = 'user';
            }
            
            \Log::info('Attempting to get Google user from Socialite', [
                'intended_role' => $intendedRole,
            ]);
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google user retrieved successfully', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'has_avatar' => !empty($googleUser->getAvatar()),
                'intended_role' => $intendedRole,
            ]);

            // Check if user exists by google_id
            $existingUserByGoogleId = User::where('google_id', $googleUser->getId())->first();
            \Log::info('User lookup by google_id', [
                'google_id' => $googleUser->getId(),
                'user_found' => !is_null($existingUserByGoogleId),
                'existing_role' => $existingUserByGoogleId ? $existingUserByGoogleId->role : null,
            ]);

            // Check if user exists by email
            $existingUserByEmail = User::where('email', $googleUser->getEmail())->first();
            \Log::info('User lookup by email', [
                'email' => $googleUser->getEmail(),
                'user_found' => !is_null($existingUserByEmail),
                'existing_role' => $existingUserByEmail ? $existingUserByEmail->role : null,
            ]);

            // Validate: Check if Google account is already used by a different role
            $user = $existingUserByGoogleId ?: $existingUserByEmail;
            
            if ($user) {
                $existingRole = $user->role;
                
                // Check if the Google account is already used by a different role
                if ($existingRole !== $intendedRole) {
                    $roleNames = [
                        'user' => 'pengguna biasa',
                        'recruiter' => 'recruiter',
                        'admin' => 'admin',
                    ];
                    
                    $existingRoleName = $roleNames[$existingRole] ?? $existingRole;
                    $intendedRoleName = $roleNames[$intendedRole] ?? $intendedRole;
                    
                    \Log::warning('Google OAuth attempted with different role', [
                        'google_id' => $googleUser->getId(),
                        'email' => $googleUser->getEmail(),
                        'existing_role' => $existingRole,
                        'intended_role' => $intendedRole,
                    ]);
                    
                    // Determine redirect route - if trying to register as recruiter but account is user, redirect to login
                    // If trying to login/register as user but account is recruiter, redirect to login with message
                    $redirectRoute = 'login';
                    if ($intendedRole === 'recruiter' && $existingRole === 'user') {
                        // Trying to register as recruiter but account is already user
                        $redirectRoute = 'register.role';
                    }
                    
                    $errorMessage = 'Akun Google ini sudah terdaftar sebagai ' . $existingRoleName . '. ';
                    if ($existingRole === 'user') {
                        $errorMessage .= 'Jika Anda ingin login sebagai pengguna biasa, silakan gunakan halaman login.';
                    } elseif ($existingRole === 'recruiter') {
                        $errorMessage .= 'Jika Anda ingin login sebagai recruiter, silakan gunakan halaman login recruiter.';
                    } else {
                        $errorMessage .= 'Satu akun Google hanya dapat digunakan untuk satu tipe akun.';
                    }
                    
                    return redirect()->route($redirectRoute)
                        ->with('status', $errorMessage)
                        ->with('toast_type', 'error');
                }
                
                // User exists with the correct role
                \Log::info('Existing user found with matching role', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'has_google_id' => !empty($user->google_id),
                ]);
                
                // If user exists but doesn't have google_id, update it
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    \Log::info('Updating user with google_id', ['user_id' => $user->id]);
                }
                
                // Update avatar if not set or if changed
                if ($googleUser->getAvatar()) {
                    if (!$user->avatar || $user->avatar !== $googleUser->getAvatar()) {
                        $user->avatar = $googleUser->getAvatar();
                        \Log::info('Updating user avatar', ['user_id' => $user->id]);
                    }
                }
                
                $user->save();
                \Log::info('User updated with Google credentials', ['user_id' => $user->id]);
            } else {
                // Create new user with intended role
                $name = $googleUser->getName();
                $nameParts = explode(' ', $name, 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : null;

                // Ensure role is valid and not admin (admin can only be created manually)
                if (!in_array($intendedRole, ['user', 'recruiter'])) {
                    \Log::warning('Invalid role attempted in Google OAuth registration', [
                        'intended_role' => $intendedRole,
                        'email' => $googleUser->getEmail(),
                    ]);
                    $intendedRole = 'user'; // Default to user for safety
                }

                \Log::info('Creating new user from Google OAuth', [
                    'email' => $googleUser->getEmail(),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'role' => $intendedRole,
                ]);

                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(32)), // Random password since using Google OAuth
                    'role' => $intendedRole, // Only 'user' or 'recruiter, never 'admin'
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(), // Google emails are verified
                ];
                
                // If recruiter, we need additional fields - but they can be filled later
                // For now, just create with basic info
                $user = User::create($userData);
                \Log::info('New user created successfully', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                ]);
                
                // If recruiter, redirect to complete profile or dashboard
                if ($intendedRole === 'recruiter') {
                    // Note: Recruiter registration usually requires additional fields
                    // This is a basic implementation - you may want to redirect to complete profile
                    \Log::warning('Recruiter created via Google OAuth without required fields', [
                        'user_id' => $user->id,
                    ]);
                }
            }

            // Login the user
            \Log::info('Logging in user', ['user_id' => $user->id, 'email' => $user->email, 'role' => $user->role]);
            Auth::login($user, true);

            // Redirect based on role
            $welcomeMessage = 'Selamat datang, ' . ($user->first_name ?? '') . ($user->last_name ?? '' ? ' ' . $user->last_name : '') . '!';
            
            if ($user->role === 'recruiter') {
                // Recruiter should stay on main domain
                $redirectRoute = 'recruiter.dashboard';
                \Log::info('Google OAuth successful, redirecting recruiter', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'redirect_route' => $redirectRoute,
                ]);
                return redirect()->route($redirectRoute)
                    ->with('status', $welcomeMessage)
                    ->with('toast_type', 'success');
            } elseif ($user->role === 'admin') {
                $adminDomain = env('ADMIN_DOMAIN', 'admin.anagataexecutive.co.id');
                $scheme = request()->getScheme();
                $url = $scheme . '://' . $adminDomain . '/dashboard';
                \Log::info('Google OAuth successful, redirecting admin', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'redirect_url' => $url,
                ]);
                return redirect($url)
                    ->with('status', $welcomeMessage)
                    ->with('toast_type', 'success');
            } else {
                $redirectRoute = 'home';
                \Log::info('Google OAuth successful, redirecting user', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'redirect_route' => $redirectRoute,
                ]);
                return redirect()->route($redirectRoute)
                    ->with('status', $welcomeMessage)
                    ->with('toast_type', 'success');
            }

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
