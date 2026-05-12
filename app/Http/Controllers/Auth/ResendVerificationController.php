<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAccountMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResendVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'method' => 'required|in:email,phone',
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Generate new OTP
        $user->otp = rand(100000, 999999);
        $user->save();

        if ($request->method === 'email') {
            Mail::to($user->email)->send(new VerifyAccountMail($user->otp, $user->email));
        } else {
            // SMS logic using Twilio could go here
            // For now, let's just log it or return a message
            \Log::info("OTP for {$user->phone}: {$user->otp}");
            return back()->with('success', 'OTP sent to your phone (Check logs for demo).');
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
