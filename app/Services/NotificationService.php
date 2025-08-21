<?php

namespace App\Services;

use App\Models\Service;
use App\Models\NotificationRead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Get renewal notifications for services that are expiring or expired
     *
     * @param int|null $userId User ID to filter seen notifications (null for current user)
     * @return array
     */
    public static function getRenewalNotifications($userId = null)
    {
        $notifications = [];
        $today = Carbon::today();

        // Use current authenticated user if no user ID provided
        if ($userId === null) {
            $userId = Auth::id();
        }

        // Get services that are expired or expiring within 30 days
        $services = Service::with(['client', 'vendor'])
            ->where('end_date', '<=', $today->copy()->addDays(30))
            ->orderBy('end_date', 'asc')
            ->get();

        foreach ($services as $service) {
            $daysLeft = $today->diffInDays($service->end_date, false);
            $notificationType = null;

            // Determine notification type and urgency
            if ($daysLeft < 0) {
                $notificationType = 'expired';

                // Skip if user has already seen this notification
                if ($userId && NotificationRead::isNotificationRead($userId, $service->id, $notificationType)) {
                    continue;
                }

                // Expired services
                $notifications[] = [
                    'id' => $service->id,
                    'type' => $notificationType,
                    'urgency' => 'high',
                    'title' => 'Service Expired',
                    'message' => "{$service->service_name} expired " . abs($daysLeft) . " days ago",
                    'client' => $service->client->cname ?? 'Unknown Client',
                    'vendor' => $service->vendor->name ?? 'Unknown Vendor',
                    'end_date' => $service->end_date,
                    'days_left' => $daysLeft,
                    'icon' => 'bx-error-circle',
                    'color' => 'text-danger',
                    'bg_color' => 'bg-light-danger',
                    'time_ago' => abs($daysLeft) . ' days ago',
                    'action_url' => route('send-mail', $service->id),
                    'action_text' => 'Send Renewal Email'
                ];
            } elseif ($daysLeft == 0) {
                $notificationType = 'expiring_today';

                // Skip if user has already seen this notification
                if ($userId && NotificationRead::isNotificationRead($userId, $service->id, $notificationType)) {
                    continue;
                }

                // Expiring today
                $notifications[] = [
                    'id' => $service->id,
                    'type' => $notificationType,
                    'urgency' => 'high',
                    'title' => 'Service Expires Today',
                    'message' => "{$service->service_name} expires today",
                    'client' => $service->client->cname ?? 'Unknown Client',
                    'vendor' => $service->vendor->name ?? 'Unknown Vendor',
                    'end_date' => $service->end_date,
                    'days_left' => $daysLeft,
                    'icon' => 'bx-time-five',
                    'color' => 'text-danger',
                    'bg_color' => 'bg-light-danger',
                    'time_ago' => 'Today',
                    'action_url' => route('send-mail', $service->id),
                    'action_text' => 'Send Renewal Email'
                ];
            } elseif ($daysLeft == 1) {
                $notificationType = 'expiring_tomorrow';

                // Skip if user has already seen this notification
                if ($userId && NotificationRead::isNotificationRead($userId, $service->id, $notificationType)) {
                    continue;
                }

                // Expiring tomorrow
                $notifications[] = [
                    'id' => $service->id,
                    'type' => $notificationType,
                    'urgency' => 'high',
                    'title' => 'Service Expires Tomorrow',
                    'message' => "{$service->service_name} expires tomorrow",
                    'client' => $service->client->cname ?? 'Unknown Client',
                    'vendor' => $service->vendor->name ?? 'Unknown Vendor',
                    'end_date' => $service->end_date,
                    'days_left' => $daysLeft,
                    'icon' => 'bx-alarm',
                    'color' => 'text-warning',
                    'bg_color' => 'bg-light-warning',
                    'time_ago' => 'Tomorrow',
                    'action_url' => route('send-mail', $service->id),
                    'action_text' => 'Send Renewal Email'
                ];
            } elseif ($daysLeft <= 7) {
                $notificationType = 'expiring_week';

                // Skip if user has already seen this notification
                if ($userId && NotificationRead::isNotificationRead($userId, $service->id, $notificationType)) {
                    continue;
                }

                // Expiring within a week
                $notifications[] = [
                    'id' => $service->id,
                    'type' => $notificationType,
                    'urgency' => 'medium',
                    'title' => 'Service Expires Soon',
                    'message' => "{$service->service_name} expires in {$daysLeft} days",
                    'client' => $service->client->cname ?? 'Unknown Client',
                    'vendor' => $service->vendor->name ?? 'Unknown Vendor',
                    'end_date' => $service->end_date,
                    'days_left' => $daysLeft,
                    'icon' => 'bx-calendar-exclamation',
                    'color' => 'text-warning',
                    'bg_color' => 'bg-light-warning',
                    'time_ago' => "In {$daysLeft} days",
                    'action_url' => route('send-mail', $service->id),
                    'action_text' => 'Send Renewal Email'
                ];
            } elseif ($daysLeft <= 30) {
                $notificationType = 'expiring_month';

                // Skip if user has already seen this notification
                if ($userId && NotificationRead::isNotificationRead($userId, $service->id, $notificationType)) {
                    continue;
                }

                // Expiring within a month
                $notifications[] = [
                    'id' => $service->id,
                    'type' => $notificationType,
                    'urgency' => 'low',
                    'title' => 'Upcoming Renewal',
                    'message' => "{$service->service_name} expires in {$daysLeft} days",
                    'client' => $service->client->cname ?? 'Unknown Client',
                    'vendor' => $service->vendor->name ?? 'Unknown Vendor',
                    'end_date' => $service->end_date,
                    'days_left' => $daysLeft,
                    'icon' => 'bx-calendar',
                    'color' => 'text-info',
                    'bg_color' => 'bg-light-info',
                    'time_ago' => "In {$daysLeft} days",
                    'action_url' => route('send-mail', $service->id),
                    'action_text' => 'Send Renewal Email'
                ];
            }
        }

        return $notifications;
    }

    /**
     * Get notification counts by urgency
     *
     * @param int|null $userId User ID to filter seen notifications (null for current user)
     * @return array
     */
    public static function getNotificationCounts($userId = null)
    {
        $notifications = self::getRenewalNotifications($userId);

        $counts = [
            'total' => count($notifications),
            'high' => 0,
            'medium' => 0,
            'low' => 0,
            'expired' => 0,
            'expiring_today' => 0,
            'expiring_week' => 0
        ];

        foreach ($notifications as $notification) {
            $counts[$notification['urgency']]++;

            if ($notification['type'] === 'expired') {
                $counts['expired']++;
            } elseif ($notification['type'] === 'expiring_today') {
                $counts['expiring_today']++;
            } elseif (in_array($notification['type'], ['expiring_tomorrow', 'expiring_week'])) {
                $counts['expiring_week']++;
            }
        }

        return $counts;
    }

    /**
     * Get the most urgent notifications (limit to top 10)
     *
     * @param int $limit
     * @param int|null $userId User ID to filter seen notifications (null for current user)
     * @return array
     */
    public static function getUrgentNotifications($limit = 10, $userId = null)
    {
        $notifications = self::getRenewalNotifications($userId);

        // Sort by urgency (high first) and then by days left
        usort($notifications, function ($a, $b) {
            $urgencyOrder = ['high' => 0, 'medium' => 1, 'low' => 2];

            if ($urgencyOrder[$a['urgency']] !== $urgencyOrder[$b['urgency']]) {
                return $urgencyOrder[$a['urgency']] - $urgencyOrder[$b['urgency']];
            }

            return $a['days_left'] - $b['days_left'];
        });

        return array_slice($notifications, 0, $limit);
    }

    /**
     * Check if there are any critical notifications (expired or expiring today/tomorrow)
     *
     * @param int|null $userId User ID to filter seen notifications (null for current user)
     * @return bool
     */
    public static function hasCriticalNotifications($userId = null)
    {
        $counts = self::getNotificationCounts($userId);
        return $counts['expired'] > 0 || $counts['expiring_today'] > 0;
    }
}
