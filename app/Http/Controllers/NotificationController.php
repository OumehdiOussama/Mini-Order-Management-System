<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markRead(Request $request, $id)
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
