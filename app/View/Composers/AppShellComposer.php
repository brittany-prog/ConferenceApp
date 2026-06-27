<?php

namespace App\View\Composers;

use App\Support\AppSettings;
use App\Support\NotificationCenter;
use Illuminate\View\View;

class AppShellComposer
{
    public function compose(View $view): void
    {
        $unreadMessagesCount = 0;
        $notificationsCount = 0;
        $notificationPreview = collect();

        if (auth()->check()) {
            $unreadMessagesCount = auth()->user()
                ->receivedMessages()
                ->whereNull('read_at')
                ->count();

            $notifications = NotificationCenter::for(auth()->user());
            $notificationsCount = $notifications['count'];
            $notificationPreview = $notifications['preview'];
        }

        $view->with('appSettings', AppSettings::all())
            ->with('unreadMessagesCount', $unreadMessagesCount)
            ->with('notificationsCount', $notificationsCount)
            ->with('notificationPreview', $notificationPreview);
    }
}
