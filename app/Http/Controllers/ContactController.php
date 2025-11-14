<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactEmail;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
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
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Combine first_name and last_name into name
        $data = [
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'message' => $validated['message'],
        ];

        // Generate WhatsApp URL (always generate, even if email fails)
        $whatsappPhone = config('whatsapp.recipient_phone');
        $whatsappUrl = null;
        if (!empty($whatsappPhone)) {
            try {
                // Log the phone number for debugging
                Log::info('WhatsApp phone number from config', ['phone' => $whatsappPhone, 'length' => strlen($whatsappPhone)]);
                
                $whatsappMessage = $this->whatsappService->formatContactMessage($data);
                $whatsappUrl = $this->whatsappService->generateWhatsAppUrl($whatsappPhone, $whatsappMessage);
                
                // Log the generated URL (without message for privacy)
                Log::info('WhatsApp URL generated successfully', ['url_base' => parse_url($whatsappUrl, PHP_URL_SCHEME) . '://' . parse_url($whatsappUrl, PHP_URL_HOST) . parse_url($whatsappUrl, PHP_URL_PATH)]);
            } catch (\Exception $e) {
                Log::error('WhatsApp URL generation error: ' . $e->getMessage(), [
                    'phone' => $whatsappPhone,
                    'phone_length' => strlen($whatsappPhone ?? ''),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } else {
            Log::warning('WhatsApp recipient phone not configured. Please set WHATSAPP_RECIPIENT_PHONE in .env');
        }

        // Send email via queue/job (async - faster response for user)
        // Email sending is enabled by default (set ENABLE_CONTACT_EMAIL=false in .env to disable)
        $emailSent = false;
        $emailEnabled = env('ENABLE_CONTACT_EMAIL', true); // Enabled by default
        
        if ($emailEnabled) {
            try {
                // Use MAIL_FROM_ADDRESS as recipient (or set MAIL_TO_ADDRESS in .env)
                $recipient = env('MAIL_TO_ADDRESS', config('mail.from.address'));
                
                // Dispatch email job to queue (runs in background - faster response)
                SendContactEmail::dispatch($data, $recipient);
                $emailSent = true; // Consider it sent (will be processed in background)
                
                Log::info('Contact form email queued successfully', ['recipient' => $recipient]);
            } catch (\Exception $e) {
                Log::error('Email queue error: ' . $e->getMessage(), [
                    'data' => $data,
                    'recipient' => $recipient ?? 'not set',
                    'trace' => $e->getTraceAsString(),
                ]);
                // Continue even if email queue fails - WhatsApp will still work
            }
        } else {
            // Email sending is disabled - code remains but won't execute
            Log::info('Contact form email sending is disabled. Set ENABLE_CONTACT_EMAIL=true in .env to enable.');
        }

        // Always return success with WhatsApp URL
        $statusMessage = 'Pesan Anda telah kami terima. Tim kami akan segera menghubungi Anda.';
        if (!$emailSent && $whatsappUrl) {
            $statusMessage = 'Pesan Anda telah kami terima. Silakan lanjutkan melalui WhatsApp.';
        }

        return back()
            ->with('status', $statusMessage)
            ->with('whatsapp_url', $whatsappUrl);
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
