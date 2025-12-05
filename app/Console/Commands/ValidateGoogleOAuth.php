<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidateGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:oauth:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate Google OAuth configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Validating Google OAuth Configuration...');
        $this->newLine();

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');
        $appUrl = config('app.url');

        // Check Client ID
        $this->line('1. Checking Client ID...');
        if (empty($clientId)) {
            $this->error('   ❌ GOOGLE_CLIENT_ID is not set in .env file');
        } else {
            $this->info('   ✓ GOOGLE_CLIENT_ID is set');
            $this->line('      Value: ' . substr($clientId, 0, 30) . '...');
            
            // Validate format
            if (!str_contains($clientId, '.apps.googleusercontent.com')) {
                $this->warn('   ⚠ Client ID format might be incorrect (should end with .apps.googleusercontent.com)');
            }
        }
        $this->newLine();

        // Check Client Secret
        $this->line('2. Checking Client Secret...');
        if (empty($clientSecret)) {
            $this->error('   ❌ GOOGLE_CLIENT_SECRET is not set in .env file');
        } else {
            $this->info('   ✓ GOOGLE_CLIENT_SECRET is set');
            $this->line('      Length: ' . strlen($clientSecret) . ' characters');
        }
        $this->newLine();

        // Check Redirect URI
        $this->line('3. Checking Redirect URI...');
        if (empty($redirectUri)) {
            $redirectUri = route('google.callback');
            $this->warn('   ⚠ GOOGLE_REDIRECT_URI is not set, using auto-generated:');
        } else {
            $this->info('   ✓ GOOGLE_REDIRECT_URI is set');
        }
        $this->line('      Current: ' . $redirectUri);
        
        // Check for localhost vs 127.0.0.1 mismatch
        $parsedRedirect = parse_url($redirectUri);
        $parsedAppUrl = parse_url($appUrl);
        if ($parsedRedirect && $parsedAppUrl && 
            isset($parsedRedirect['host']) && isset($parsedAppUrl['host'])) {
            if (($parsedAppUrl['host'] === 'localhost' && $parsedRedirect['host'] === '127.0.0.1') ||
                ($parsedAppUrl['host'] === '127.0.0.1' && $parsedRedirect['host'] === 'localhost')) {
                $this->error('   ❌ CRITICAL: Hostname mismatch detected!');
                $this->line('      APP_URL uses: ' . $parsedAppUrl['host']);
                $this->line('      Redirect URI uses: ' . $parsedRedirect['host']);
                $this->line('      Google OAuth treats these as different domains!');
                $this->line('      Solution: Use the same hostname in both APP_URL and GOOGLE_REDIRECT_URI');
                $this->line('      Recommended: Use "localhost" for both in development');
            }
        }
        $this->newLine();

        // Check APP_URL
        $this->line('4. Checking APP_URL...');
        if (empty($appUrl)) {
            $this->warn('   ⚠ APP_URL is not set');
        } else {
            $this->info('   ✓ APP_URL is set');
            $this->line('      Value: ' . $appUrl);
            
            // Check if redirect URI matches APP_URL
            $parsedAppUrl = parse_url($appUrl);
            $parsedRedirectUri = parse_url($redirectUri);
            
            if ($parsedAppUrl && $parsedRedirectUri) {
                if ($parsedAppUrl['scheme'] !== $parsedRedirectUri['scheme'] || 
                    $parsedAppUrl['host'] !== $parsedRedirectUri['host']) {
                    $this->warn('   ⚠ Redirect URI host/scheme does not match APP_URL');
                    $this->line('      APP_URL: ' . $parsedAppUrl['scheme'] . '://' . $parsedAppUrl['host']);
                    $this->line('      Redirect URI: ' . $parsedRedirectUri['scheme'] . '://' . $parsedRedirectUri['host']);
                }
            }
        }
        $this->newLine();

        // Summary and recommendations
        $this->info('=== Summary ===');
        $this->newLine();
        
        if (empty($clientId) || empty($clientSecret)) {
            $this->error('❌ Configuration is incomplete. Please set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env file.');
            return 1;
        }

        $this->info('✓ Basic configuration is present.');
        $this->newLine();
        
        $this->line('=== Next Steps ===');
        $this->line('1. Verify in Google Cloud Console:');
        $this->line('   - Go to: https://console.cloud.google.com/apis/credentials');
        $this->line('   - Find your OAuth 2.0 Client ID');
        $this->line('   - Check that it matches: ' . substr($clientId, 0, 30) . '...');
        $this->newLine();
        $this->line('2. Verify Redirect URI in Google Cloud Console:');
        $this->line('   - In your OAuth client settings, check "Authorized redirect URIs"');
        $this->line('   - Make sure this exact URI is added:');
        $this->line('     ' . $redirectUri);
        $this->newLine();
        $this->line('3. Check OAuth Consent Screen:');
        $this->line('   - Go to: https://console.cloud.google.com/apis/credentials/consent');
        $this->line('   - Ensure it\'s properly configured');
        $this->line('   - Add required scopes: email, profile, openid');
        $this->newLine();
        $this->line('4. If still getting "invalid_client" error:');
        $this->line('   - The Client ID might have been deleted or disabled');
        $this->line('   - Create a new OAuth 2.0 Client ID in Google Cloud Console');
        $this->line('   - Update GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env');
        $this->line('   - Run: php artisan config:clear');
        
        return 0;
    }
}

