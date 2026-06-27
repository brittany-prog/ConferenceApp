<?php

namespace App\Mail;

use App\Models\User;
use App\Support\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountSetupLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $setupUrl
    ) {
    }

    public function build(): self
    {
        $brandName = AppSettings::all()['brand_name'] ?? 'your event';

        return $this
            ->subject('Set up your '.$brandName.' password')
            ->view('emails.auth.account-setup-link');
    }
}
