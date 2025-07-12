<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\UserNotification;

class NotificationService
{
    /**
     * Gửi thông báo cho một hoặc nhiều user
     * @param array $userIds
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string|null $thumbnail
     * @return Notification
     */
    public static function send(array $userIds, string $title, string $content, string $type = 'Khác', string $thumbnail = null): Notification
    {
        $notification = Notification::create([
            'name' => $title,
            'content' => $content,
            'notification_type' => $type,
            'thumbnail' => $thumbnail,
        ]);

        foreach ($userIds as $userId) {
            UserNotification::create([
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'notification_status' => 'unread',
            ]);
        }

        return $notification;
    }
}
