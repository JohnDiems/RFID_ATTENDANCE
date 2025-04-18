<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssumptionLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $link)
    {
        $this->subject = $subject;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('EmailVarification.EmailSender')
        ->subject($this->subject)
        ->with([
            'link' => $this->link,
        ]);
    }

    // /**
    // //  * Get the message envelope.
    // //  */
    // // public function envelope(): Envelope
    // // {
    // //     return new Envelope(
    // //         subject: 'Assumption Link Mail',
    // //     );
    // // }

    // // /**
    // //  * Get the message content definition.
    // //  */
    // // public function content(): Content
    // // {
    // //     return new Content(
    // //         view: 'view.name',
    // //     );
    // // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
