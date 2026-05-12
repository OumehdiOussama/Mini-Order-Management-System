<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read for the authenticated user.
     * Clears the notification cache so the header updates immediately.
     */
    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        // Clear the cache so the header dropdown reflects changes immediately
        cache()->forget('user_notifications_' . auth()->id());

        return response()->json(['success' => true]);
    }

    /**
     * Mark a specific notification as read.
     * Clears the notification cache so the header updates immediately.
     */
    public function markRead(Request $request, $id)
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        // Clear the cache so the header dropdown reflects changes immediately
        cache()->forget('user_notifications_' . auth()->id());

        return response()->json(['success' => true]);
    }
}
