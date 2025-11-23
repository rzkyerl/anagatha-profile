<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // GoogleSheetService will be resolved lazily to handle configuration errors gracefully

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Honeypot field - real users should leave this empty
        if (!empty($request->input('company'))) {
            Log::warning('Contact form honeypot triggered', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return back()
                ->with('status', 'We received your message.')
                ->with('toast_type', 'success');
        }

        // XSS detection BEFORE validation - check for script tags or javascript:
        $allInputs = implode(' ', array_filter([
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('message'),
        ]));
        $xssPatterns = ['<script', 'javascript:', 'onerror=', 'onload=', 'onclick=', 'onmouseover=', '<img', 'onerror'];
        foreach ($xssPatterns as $pattern) {
            if (stripos($allInputs, $pattern) !== false) {
                Log::warning('XSS attempt detected in contact form', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'pattern' => $pattern,
                ]);
                return back()
                    ->with('status', 'Invalid characters detected in your message. Please use only plain text and avoid special characters like < > or script tags.')
                    ->with('toast_type', 'warning')
                    ->withInput();
            }
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:4', 'max:60', 'regex:/^[^<>]*$/'],
            'last_name' => ['nullable', 'string', 'max:60', 'regex:/^[^<>]*$/'],
            'email' => ['required', 'email', 'max:35'],
            'phone' => [
                'required',
                'string',
                'max:25', // Increased to accommodate format like (+62) + space + 15 digits
                'regex:/^\(\+\d{1,2}\)\s?\d+$/',
                function ($attribute, $value, $fail) {
                    // Extract country code and phone number part
                    if (!preg_match('/^\((\+\d{1,2})\)\s?(.*)$/', $value, $matches)) {
                        $fail('The phone number must be in format (+X) YYYYYYYY or (+XX) YYYYYYYY.');
                        return;
                    }
                    
                    $phoneNumberPart = $matches[2] ?? '';
                    // Extract only digits from phone number part
                    $phoneDigits = preg_replace('/\D/', '', $phoneNumberPart);
                    
                    // Validate digit count (8-15 digits after country code)
                    if (strlen($phoneDigits) < 8) {
                        $fail('The phone number must be at least 8 digits after country code.');
                    }
                    if (strlen($phoneDigits) > 15) {
                        $fail('The phone number must not exceed 15 digits after country code.');
                    }
                },
            ],
            'message' => ['required', 'string', 'min:10', 'max:2000', 'regex:/^[^<>]*$/'],
        ]);

        // Sanitize input - strip any remaining HTML tags and trim whitespace
        $data = [
            'name' => trim(strip_tags($validated['first_name'] . ' ' . $validated['last_name'])),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'phone' => $validated['phone'] ? trim(strip_tags($validated['phone'])) : '',
            'message' => trim(strip_tags($validated['message'])),
        ];

        // Submit directly to Google Sheets via Service Account
        try {
            // Resolve service lazily to catch constructor exceptions
            $googleSheetService = app(GoogleSheetService::class);
            $submitted = $googleSheetService->appendContact($data);
            $statusMessage = $submitted
                ? 'Thank you, your message has been received and successfully logged to our system.'
                : 'We received your message, but there was an issue logging it to our system. Our team will follow up manually.';
            $toastType = $submitted ? 'success' : 'error';
        } catch (\RuntimeException $e) {
            // Handle configuration errors (missing credentials or env vars)
            $errorMsg = $e->getMessage();
            Log::error('Google Sheets configuration error', [
                'message' => $errorMsg,
                'data' => $data,
                'spreadsheet_id' => config('google_sheets.spreadsheet_id'),
                'sheet_name' => config('google_sheets.sheet_name'),
                'has_credentials_json' => !empty(env('GOOGLE_CREDENTIALS_JSON')),
                'has_credentials_base64' => !empty(env('GOOGLE_CREDENTIALS_BASE64')),
                'has_credentials_path' => !empty(env('GOOGLE_CREDENTIALS_PATH')),
                'default_credentials_exists' => file_exists(storage_path('app/google/credentials.json')),
            ]);
            $statusMessage = 'We received your message, but there was a configuration issue. Our team will follow up manually.';
            $toastType = 'error';
        } catch (\Throwable $e) {
            // Handle any other errors
            $errorMsg = $e->getMessage();
            Log::error('Google Sheets error', [
                'message' => $errorMsg,
                'class' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data,
                'spreadsheet_id' => config('google_sheets.spreadsheet_id'),
                'sheet_name' => config('google_sheets.sheet_name'),
                'trace' => $e->getTraceAsString(),
            ]);
            $statusMessage = 'We received your message, but there was an issue logging it to our system. Our team will follow up manually.';
            $toastType = 'error';
        }

        return back()
            ->with('status', $statusMessage)
            ->with('toast_type', $toastType);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Optional: implement if needed
    }
}
