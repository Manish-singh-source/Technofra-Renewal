<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'notification_type',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that marked the notification as read.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service associated with the notification.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Check if a specific notification has been read by a user.
     *
     * @param int $userId
     * @param int $serviceId
     * @param string $notificationType
     * @return bool
     */
    public static function isNotificationRead(int $userId, int $serviceId, string $notificationType): bool
    {
        return self::where('user_id', $userId)
            ->where('service_id', $serviceId)
            ->where('notification_type', $notificationType)
            ->exists();
    }

    /**
     * Mark a notification as read for a user.
     *
     * @param int $userId
     * @param int $serviceId
     * @param string $notificationType
     * @return self
     */
    public static function markAsRead(int $userId, int $serviceId, string $notificationType): self
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'service_id' => $serviceId,
                'notification_type' => $notificationType,
            ],
            [
                'read_at' => now(),
            ]
        );
    }
}
