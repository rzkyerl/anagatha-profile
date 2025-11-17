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
        $this->spreadsheetId = (string) config('google_sheets.spreadsheet_id', '');
        $this->sheetName = (string) config('google_sheets.sheet_name', 'Sheet1');

        if (empty($this->spreadsheetId)) {
            throw new \RuntimeException('Missing GOOGLE_SHEET_ID in environment.');
        }

        $client = new Client();
        
        // Try credentials from environment variable first (for Railway/cloud)
        $credentialsJson = env('GOOGLE_CREDENTIALS_JSON');
        if (!empty($credentialsJson)) {
            $credentials = json_decode($credentialsJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid GOOGLE_CREDENTIALS_JSON: ' . json_last_error_msg());
            }
            $client->setAuthConfig($credentials);
        } else {
            // Fallback to file path (for local development)
            $credentialsPath = storage_path('app/google/credentials.json');
            if (empty($credentialsPath) || !file_exists($credentialsPath)) {
                throw new \RuntimeException('Google credentials not found. Set GOOGLE_CREDENTIALS_JSON env variable or place credentials.json at: ' . $credentialsPath);
            }
            $client->setAuthConfig($credentialsPath);
        }

        $client->setScopes([Sheets::SPREADSHEETS]);
        $this->sheetsService = new Sheets($client);
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
        } catch (\Throwable $e) {
            Log::error('Google Sheets append error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}


