<?php

namespace App\Mail;

use App\Models\User;
use App\Support\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $resetUrl
    ) {
    }

    public function build(): self
    {
        $brandName = AppSettings::all()['brand_name'] ?? 'your event';

        return $this
            ->subject('Reset your '.$brandName.' password')
            ->view('emails.auth.password-reset-link');
    }
}
