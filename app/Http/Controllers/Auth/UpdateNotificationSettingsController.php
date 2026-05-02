<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateNotificationSettingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Handle both Form submission and JSON AJAX request
        if ($request->isJson()) {
            $settings = $request->all();
        } else {
            $settings = [
                'email' => $request->has('email_notifications'),
                'in_app' => $request->has('in_app_alerts'),
                'sms' => $request->has('sms_notifications'),
            ];
        }

        auth()->user()->update([
            'notification_settings' => $settings
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification settings updated successfully!');
    }
}
