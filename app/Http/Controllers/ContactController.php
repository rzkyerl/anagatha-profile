<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        // Generate WhatsApp URL (for fallback - frontend generates it faster)
        // Frontend JavaScript generates WhatsApp URL immediately, this is just for session fallback
        $whatsappPhone = config('whatsapp.recipient_phone');
        $whatsappUrl = null;
        if (!empty($whatsappPhone)) {
            try {
                $whatsappMessage = $this->whatsappService->formatContactMessage($data);
                $whatsappUrl = $this->whatsappService->generateWhatsAppUrl($whatsappPhone, $whatsappMessage);
            } catch (\Exception $e) {
                Log::error('WhatsApp URL generation error: ' . $e->getMessage());
            }
        }

        // Send email directly (synchronous)
        // Email sending is enabled by default (set ENABLE_CONTACT_EMAIL=false in .env to disable)
        $emailSent = false;
        $emailEnabled = env('ENABLE_CONTACT_EMAIL', true); // Enabled by default
        
        if ($emailEnabled) {
            try {
                // Use MAIL_FROM_ADDRESS as recipient (or set MAIL_TO_ADDRESS in .env)
                $recipient = env('MAIL_TO_ADDRESS', config('mail.from.address'));
                
                // Send email directly
                Mail::to($recipient)->send(new ContactMessage($data));
                $emailSent = true;
                Log::info('Contact form email sent successfully', [
                    'recipient' => $recipient,
                    'name' => $data['name'],
                ]);
            } catch (\Exception $e) {
                Log::error('Email send error: ' . $e->getMessage(), [
                    'data' => $data,
                    'recipient' => $recipient ?? 'not set',
                    'trace' => $e->getTraceAsString(),
                ]);
                // Continue even if email fails - WhatsApp will still work
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
