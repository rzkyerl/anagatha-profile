<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    private Sheets $sheetsService;
    private string $spreadsheetId;
    private string $sheetName;

    public function __construct()
    {
        $credentials = $this->resolveCredentials();

        $this->spreadsheetId = (string) config('google_sheets.spreadsheet_id', '');
        $this->sheetName = (string) config('google_sheets.sheet_name', 'Sheet1');

        // Validate required credential fields
        if (empty($credentials['client_email']) || empty($credentials['private_key'])) {
            Log::error('Google Sheets credentials missing required fields', [
                'has_client_email' => !empty($credentials['client_email']),
                'has_private_key' => !empty($credentials['private_key']),
            ]);
            throw new \RuntimeException('Google credentials are missing required fields (client_email or private_key).');
        }
        
        if (empty($this->spreadsheetId)) {
            Log::error('Google Sheets configuration missing', [
                'spreadsheet_id' => $this->spreadsheetId,
                'sheet_name' => $this->sheetName,
            ]);
            throw new \RuntimeException('Missing GOOGLE_SHEET_ID in environment.');
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentials);
            $client->setScopes([Sheets::SPREADSHEETS]);

            $this->sheetsService = new Sheets($client);
        } catch (\Throwable $e) {
            Log::error('Google Sheets client initialization error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'has_credentials' => !empty($credentials),
                'client_exists' => class_exists('Google\Client'),
                'sheets_exists' => class_exists('Google\Service\Sheets'),
            ]);
            throw new \RuntimeException('Failed to initialize Google Sheets client: ' . $e->getMessage());
        }
    }

    /**
     * Resolve Google credentials from JSON, Base64, or file path.
     *
     * @throws \RuntimeException
     */
    private function resolveCredentials(): array
    {
        $json = $this->getCredentialsJson();

        $credentials = $json ? json_decode($json, true) : null;

        if (empty($credentials) || !is_array($credentials)) {
            Log::error('Google Sheets credentials invalid or missing', [
                'has_json' => !empty($json),
                'json_valid' => json_last_error() === JSON_ERROR_NONE,
                'json_error' => json_last_error_msg(),
            ]);
            throw new \RuntimeException(
                'Google credentials are missing or invalid. Provide them via '
                . 'GOOGLE_CREDENTIALS_JSON, GOOGLE_CREDENTIALS_BASE64, GOOGLE_CREDENTIALS_PATH, '
                . 'or place the file at storage/app/google/credentials.json.'
            );
        }

        return $credentials;
    }

    private function getCredentialsJson(): ?string
    {
        $inlineJson = env('GOOGLE_CREDENTIALS_JSON');
        if (!empty($inlineJson)) {
            return $inlineJson;
        }

        $base64Credentials = env('GOOGLE_CREDENTIALS_BASE64');
        if (!empty($base64Credentials)) {
            $decoded = base64_decode($base64Credentials, true);
            if ($decoded !== false) {
                return $decoded;
            }
            Log::error('Failed to decode GOOGLE_CREDENTIALS_BASE64 payload.');
        }

        $configuredPath = env('GOOGLE_CREDENTIALS_PATH');
        if (!empty($configuredPath) && is_readable($configuredPath)) {
            return file_get_contents($configuredPath) ?: null;
        }

        $defaultPath = storage_path('app/google/credentials.json');
        if (is_readable($defaultPath)) {
            return file_get_contents($defaultPath) ?: null;
        }

        return null;
    }

    public function appendContact(array $data): bool
    {
        try {
            $values = [[
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['message'] ?? '',
                now()->toDateTimeString(),
            ]];

            $body = new ValueRange([
                'values' => $values,
            ]);

            $range = $this->sheetName;
            $params = ['valueInputOption' => 'RAW'];

            $this->sheetsService->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            return true;
        } catch (\Google\Service\Exception $e) {
            // Google API specific errors
            $errorDetails = json_decode($e->getMessage(), true);
            $errorMessage = $e->getMessage();
            if (isset($errorDetails['error']['message'])) {
                $errorMessage = $errorDetails['error']['message'];
            }
            Log::error('Google Sheets API error: ' . $errorMessage, [
                'code' => $e->getCode(),
                'data' => $data,
                'spreadsheet_id' => $this->spreadsheetId,
                'sheet_name' => $this->sheetName,
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Google Sheets append error: ' . $e->getMessage(), [
                'class' => get_class($e),
                'code' => $e->getCode(),
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}


