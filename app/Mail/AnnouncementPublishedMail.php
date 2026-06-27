<?php

namespace App\Mail;

use App\Models\Announcement;
use App\Support\AppSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Announcement $announcement
    ) {
    }

    public function build(): self
    {
        $brandName = AppSettings::all()['brand_name'] ?? 'Event';

        return $this
            ->subject($brandName.' Update: '.$this->announcement->title)
            ->view('emails.announcements.published');
    }
}
