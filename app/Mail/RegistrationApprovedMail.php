<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public ?string $role,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Akun Anda Telah Diaktifkan')
            ->view('emails.registration-approved');
    }
}
