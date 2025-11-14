<?php

namespace App\Jobs;

use App\Mail\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;
    public string $recipient;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, string $recipient)
    {
        $this->data = $data;
        $this->recipient = $recipient;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipient)->send(new ContactMessage($this->data));
            Log::info('Contact form email sent successfully via queue', [
                'recipient' => $this->recipient,
                'name' => $this->data['name'],
            ]);
        } catch (\Exception $e) {
            Log::error('Email send error in queue: ' . $e->getMessage(), [
                'data' => $this->data,
                'recipient' => $this->recipient,
                'trace' => $e->getTraceAsString(),
            ]);
            // Re-throw to mark job as failed
            throw $e;
        }
    }
}
