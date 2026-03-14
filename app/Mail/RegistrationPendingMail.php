<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public ?string $requestedRole,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Permintaan Akun Baru (Menunggu ACC)')
            ->view('emails.registration-pending');
    }
}
