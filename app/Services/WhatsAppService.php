<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Generate WhatsApp URL with pre-filled message
     *
     * @param string $phoneNumber
     * @param string $message
     * @return string
     */
    public function generateWhatsAppUrl(string $phoneNumber, string $message): string
    {
        // Format phone number (remove +, spaces, and other non-numeric characters)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code (default to Indonesia if not provided)
        if (!str_starts_with($phoneNumber, '62') && !str_starts_with($phoneNumber, '1')) {
            // If starts with 0, remove it and add 62
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                // Assume Indonesian number
                $phoneNumber = '62' . $phoneNumber;
            }
        }

        // Encode message for URL
        $encodedMessage = urlencode($message);

        // Generate wa.me URL
        return "https://wa.me/{$phoneNumber}?text={$encodedMessage}";
    }

    /**
     * Format contact message for WhatsApp
     */
    public function formatContactMessage(array $data): string
    {
        // Build the contact info part
        $contactInfo = $data['name'] . ', ' . $data['email'];
        if (!empty($data['phone'])) {
            $contactInfo .= ', ' . $data['phone'];
        }
        
        // Format: "Halo Anagatha, I'm [contactInfo]\n[message]"
        $message = "Halo Anagatha, I'm " . $contactInfo . "\n" . $data['message'];

        return $message;
    }
}

