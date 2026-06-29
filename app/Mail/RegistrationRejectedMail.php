<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Pendaftaran Akun Ditolak')
            ->view('emails.registration-rejected');
    }
}
