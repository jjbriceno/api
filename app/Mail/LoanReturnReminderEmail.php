<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanReturnReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanDetails;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio de devolución de préstamo',
        );
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loanDetails)
    {
        $this->loanDetails = $loanDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recordatorio: Pronto debe devolver su préstamo (ID: ' . $this->loanDetails['id'] . ')')
                    ->markdown('vendor.notifications.email', $this->loanDetails);
    }
}
