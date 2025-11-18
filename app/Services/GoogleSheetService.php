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
                now('Asia/Jakarta')->toDateTimeString(),
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
            $errorCode = $e->getCode();
            
            if (isset($errorDetails['error'])) {
                $errorMessage = $errorDetails['error']['message'] ?? $errorMessage;
                $errorCode = $errorDetails['error']['code'] ?? $errorCode;
                $errorReason = $errorDetails['error']['status'] ?? null;
            }

            // More detailed logging
            Log::error('Google Sheets API error', [
                'code' => $errorCode,
                'message' => $errorMessage,
                'reason' => $errorReason ?? null,
                'full_error' => $e->getMessage(),
                'data' => $data,
                'spreadsheet_id' => $this->spreadsheetId,
                'sheet_name' => $this->sheetName,
                'trace' => $e->getTraceAsString(),
            ]);

            // Common error codes:
            // 404 = Spreadsheet not found
            // 403 = Permission denied
            // 400 = Bad request (invalid range, etc.)
            if ($errorCode == 403) {
                Log::critical('Google Sheets permission denied - Service account may not have access to spreadsheet', [
                    'spreadsheet_id' => $this->spreadsheetId,
                ]);
            } elseif ($errorCode == 404) {
                Log::critical('Google Sheets not found - Check spreadsheet ID', [
                    'spreadsheet_id' => $this->spreadsheetId,
                ]);
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('Google Sheets append error', [
                'message' => $e->getMessage(),
                'class' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Test Google Sheets connection and configuration
     * Returns array with status and error details
     */
    public function testConnection(): array
    {
        $result = [
            'status' => 'ok',
            'message' => 'Connection successful',
            'details' => [],
        ];

        try {
            // Test 1: Check credentials
            $credentials = $this->resolveCredentials();
            $result['details']['credentials'] = [
                'has_client_email' => !empty($credentials['client_email']),
                'has_private_key' => !empty($credentials['private_key']),
                'client_email' => $credentials['client_email'] ?? null,
            ];

            // Test 2: Check spreadsheet ID
            $result['details']['spreadsheet_id'] = $this->spreadsheetId;
            $result['details']['sheet_name'] = $this->sheetName;

            // Test 3: Try to read spreadsheet metadata (this will fail if no access)
            try {
                $spreadsheet = $this->sheetsService->spreadsheets->get($this->spreadsheetId);
                $sheets = $spreadsheet->getSheets();
                $sheetNames = [];
                foreach ($sheets as $sheet) {
                    $sheetNames[] = $sheet->getProperties()->getTitle();
                }
                $result['details']['spreadsheet'] = [
                    'title' => $spreadsheet->getProperties()->getTitle(),
                    'sheets_count' => count($sheets),
                    'available_sheets' => $sheetNames,
                ];
            } catch (\Google\Service\Exception $e) {
                $errorDetails = json_decode($e->getMessage(), true);
                $errorMessage = $e->getMessage();
                $errorCode = $e->getCode();
                
                if (isset($errorDetails['error'])) {
                    $errorMessage = $errorDetails['error']['message'] ?? $errorMessage;
                    $errorCode = $errorDetails['error']['code'] ?? $errorCode;
                }

                // 404 = Spreadsheet not found or no access
                // 403 = Permission denied
                if ($errorCode == 404) {
                    $result['status'] = 'error';
                    $result['message'] = 'Spreadsheet not found or service account has no access';
                    $result['details']['error'] = [
                        'code' => $errorCode,
                        'message' => $errorMessage,
                        'suggestion' => 'Make sure the service account email has access to the spreadsheet',
                    ];
                } elseif ($errorCode == 403) {
                    $result['status'] = 'error';
                    $result['message'] = 'Permission denied: Service account cannot access the spreadsheet';
                    $result['details']['error'] = [
                        'code' => $errorCode,
                        'message' => $errorMessage,
                        'suggestion' => 'Share the spreadsheet with the service account email: ' . ($credentials['client_email'] ?? 'N/A'),
                    ];
                } else {
                    $result['status'] = 'error';
                    $result['message'] = 'Unable to access spreadsheet';
                    $result['details']['error'] = [
                        'code' => $errorCode,
                        'message' => $errorMessage,
                    ];
                }
                return $result;
            }

            // Test 4: Try to read the sheet to verify it exists
            try {
                $response = $this->sheetsService->spreadsheets_values->get(
                    $this->spreadsheetId,
                    $this->sheetName . '!A1'
                );
                $result['details']['sheet_access'] = 'ok';
            } catch (\Google\Service\Exception $e) {
                $errorDetails = json_decode($e->getMessage(), true);
                $errorMessage = $e->getMessage();
                
                if (isset($errorDetails['error']['message'])) {
                    $errorMessage = $errorDetails['error']['message'];
                }

                // Sheet not found
                if (strpos($errorMessage, 'Unable to parse range') !== false || 
                    strpos($errorMessage, 'does not exist') !== false) {
                    $result['status'] = 'warning';
                    $result['message'] = 'Sheet name might be incorrect';
                    $result['details']['sheet_error'] = $errorMessage;
                    $result['details']['configured_sheet_name'] = $this->sheetName;
                    
                    // Get available sheet names from previous test
                    if (isset($result['details']['spreadsheet']['available_sheets'])) {
                        $availableSheets = $result['details']['spreadsheet']['available_sheets'];
                        $result['details']['suggestion'] = 'Configured sheet name "' . $this->sheetName . '" not found. Available sheet names: ' . implode(', ', $availableSheets);
                        $result['details']['recommended_sheet_name'] = !empty($availableSheets) ? $availableSheets[0] : null;
                    } else {
                        $result['details']['suggestion'] = 'Check if the sheet name "' . $this->sheetName . '" exists in the spreadsheet. Open the spreadsheet and check the tab name at the bottom.';
                    }
                }
            }

        } catch (\RuntimeException $e) {
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
            $result['details']['error'] = [
                'class' => get_class($e),
                'message' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            $result['status'] = 'error';
            $result['message'] = 'Unexpected error: ' . $e->getMessage();
            $result['details']['error'] = [
                'class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return $result;
    }
}