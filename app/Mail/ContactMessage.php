<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('New Contact Message from ' . $this->data['name'] . ' - Anagata Executive')
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.contact-message')
            ->with([
                'data' => $this->data,
            ]);
    }
}
