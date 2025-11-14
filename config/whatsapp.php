<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your WhatsApp recipient phone number.
    | This will be used to generate wa.me links for contact form submissions.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Phone Number
    |--------------------------------------------------------------------------
    |
    | The phone number where contact form messages should be sent.
    | Format: Country code + number (e.g., 6281234567890 for Indonesia)
    | Or: 081234567890 (will be converted to 6281234567890)
    |
    */

    'recipient_phone' => env('WHATSAPP_RECIPIENT_PHONE', ''),
];

