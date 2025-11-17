<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected GoogleSheetService $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

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
            'first_name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[^<>]*$/'],
            'last_name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[^<>]*$/'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[^<>]*$/'],
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
        $submitted = $this->googleSheetService->appendContact($data);
        $statusMessage = $submitted
            ? 'Thank you, your message has been received and successfully logged to our system.'
            : 'We received your message, but there was an issue logging it to our system. Our team will follow up manually.';
        $toastType = $submitted ? 'success' : 'error';

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
