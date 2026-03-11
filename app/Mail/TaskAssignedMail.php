<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Mail subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Task Assigned',
        );
    }

    /**
     * Mail view
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.task_assigned',
        );
    }

    /**
     * Attachments
     */
    public function attachments(): array
    {
        return [];
    }
}