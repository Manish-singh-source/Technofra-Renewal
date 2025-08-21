<?php

namespace App\Http\View\Composers;

use App\Services\NotificationService;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get renewal notifications
        $renewalNotifications = NotificationService::getUrgentNotifications(10);
        $notificationCounts = NotificationService::getNotificationCounts();
        $hasCriticalNotifications = NotificationService::hasCriticalNotifications();

        $view->with([
            'renewalNotifications' => $renewalNotifications,
            'notificationCounts' => $notificationCounts,
            'hasCriticalNotifications' => $hasCriticalNotifications
        ]);
    }
}
