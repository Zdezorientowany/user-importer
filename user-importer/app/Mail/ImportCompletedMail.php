<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\UserImport;
use Spatie\Activitylog\Models\Activity;

class ImportCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userImport;
    public $failures;

    /**
     * Create a new message instance.
     */
    public function __construct(UserImport $userImport)
    {
        $this->userImport = $userImport;

        // Fetch detailed failure logs from activity log

        $this->failures = Activity::all()
            ->where('subject_type', UserImport::class)
            ->where('subject_id', $userImport->id)
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'row' => $activity->properties['row'] ?? [],
                    'errors' => $activity->properties['errors'] ?? [],
                ];
            });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Import Completed'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.import_completed',
            with: [
                'userImport' => $this->userImport,
                'failures' => $this->failures,
            ],
        );
    }
}
