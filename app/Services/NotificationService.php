<?php

namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Send an in-app notification and optional email alert to a user.
     */
    public static function send(User $user, string $title, string $message, string $type = 'info', ?string $actionUrl = null): CustomNotification
    {
        return CustomNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
            'is_read' => false,
        ]);
    }

    /**
     * Notify multiple users (e.g., all admins).
     */
    public static function sendToRole(string $role, string $title, string $message, string $type = 'info', ?string $actionUrl = null): void
    {
        $users = User::role($role)->get();
        foreach ($users as $user) {
            self::send($user, $title, $message, $type, $actionUrl);
        }
    }
}
