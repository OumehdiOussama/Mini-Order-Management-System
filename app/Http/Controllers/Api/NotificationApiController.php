<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationApiController extends Controller
{
    /**
     * Get user notifications as JSON.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cacheKey = 'user_notifications_api_' . $user->id;

        return Cache::remember($cacheKey, 60, function () use ($user) {
            return $user->notifications()
                ->latest()
                ->take(15)
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'data'       => $n->data,
                    'read_at'    => $n->read_at,
                    'created_at' => $n->created_at,
                ]);
        });
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        Cache::forget('user_notifications_api_' . $request->user()->id);
        Cache::forget('user_notifications_' . $request->user()->id);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        
        Cache::forget('user_notifications_api_' . $request->user()->id);
        Cache::forget('user_notifications_' . $request->user()->id);

        return response()->json(['success' => true]);
    }
}
