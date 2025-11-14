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
        
        // Validate phone number is not empty
        if (empty($phoneNumber)) {
            throw new \InvalidArgumentException('Phone number cannot be empty');
        }
        
        // Ensure it starts with country code (default to Indonesia if not provided)
        if (!str_starts_with($phoneNumber, '62') && !str_starts_with($phoneNumber, '1')) {
            // If starts with 0, remove it and add 62
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                // Only add 62 if the number doesn't already start with it and has enough digits
                // Minimum Indonesian mobile number is 10 digits (after country code)
                if (strlen($phoneNumber) >= 10) {
                    $phoneNumber = '62' . $phoneNumber;
                }
            }
        }
        
        // Validate final phone number format (should be at least 10 digits after country code)
        // Indonesian numbers: 62 + 8-12 digits
        if (str_starts_with($phoneNumber, '62')) {
            $numberWithoutCountryCode = substr($phoneNumber, 2);
            if (strlen($numberWithoutCountryCode) < 8 || strlen($numberWithoutCountryCode) > 12) {
                throw new \InvalidArgumentException('Invalid Indonesian phone number format. Should be 62 followed by 8-12 digits.');
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

