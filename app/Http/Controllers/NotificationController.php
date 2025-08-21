<?php

namespace App\Http\Controllers;

use App\Models\NotificationRead;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get all renewal notifications as JSON
     *
     * @return JsonResponse
     */
    public function getRenewalNotifications(): JsonResponse
    {
        $notifications = NotificationService::getRenewalNotifications();
        $counts = NotificationService::getNotificationCounts();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'counts' => $counts,
            'has_critical' => NotificationService::hasCriticalNotifications()
        ]);
    }

    /**
     * Get notification counts only
     *
     * @return JsonResponse
     */
    public function getNotificationCounts(): JsonResponse
    {
        $counts = NotificationService::getNotificationCounts();
        
        return response()->json([
            'success' => true,
            'counts' => $counts,
            'has_critical' => NotificationService::hasCriticalNotifications()
        ]);
    }

    /**
     * Get urgent notifications only
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUrgentNotifications(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $notifications = NotificationService::getUrgentNotifications($limit);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'total' => count($notifications)
        ]);
    }

    /**
     * Mark notification as seen/read
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer|exists:services,id',
            'notification_type' => 'required|string|in:expired,expiring_today,expiring_tomorrow,expiring_week,expiring_month'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            NotificationRead::markAsRead(
                $userId,
                $request->get('service_id'),
                $request->get('notification_type')
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as seen'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as seen'
            ], 500);
        }
    }

    /**
     * Mark all notifications as seen for the current user
     *
     * @return JsonResponse
     */
    public function markAllAsRead(): JsonResponse
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            // Get all current notifications for the user
            $notifications = NotificationService::getRenewalNotifications($userId);

            // Mark each notification as read
            foreach ($notifications as $notification) {
                NotificationRead::markAsRead(
                    $userId,
                    $notification['id'],
                    $notification['type']
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as seen',
                'count' => count($notifications)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as seen'
            ], 500);
        }
    }

    /**
     * Get notification summary for dashboard widget
     *
     * @return JsonResponse
     */
    public function getNotificationSummary(): JsonResponse
    {
        $counts = NotificationService::getNotificationCounts();
        $urgentNotifications = NotificationService::getUrgentNotifications(5);
        
        return response()->json([
            'success' => true,
            'summary' => [
                'total_alerts' => $counts['total'],
                'expired_services' => $counts['expired'],
                'expiring_today' => $counts['expiring_today'],
                'expiring_this_week' => $counts['expiring_week'],
                'has_critical' => NotificationService::hasCriticalNotifications(),
                'urgent_notifications' => $urgentNotifications
            ]
        ]);
    }
}
