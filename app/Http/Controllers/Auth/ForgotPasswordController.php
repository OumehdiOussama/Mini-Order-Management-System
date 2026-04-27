<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Mail\SendResetLinkMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $token = Str::random(60);

        DB::table("password_reset_tokens")->updateOrInsert(
            ["email" => $request->email],
            ["token" =>$token, "created_at" => now()]
        );

        Mail::to($request->email)->send(new SendResetLinkMail($token));
        
        return back()->with("success","We’ve sent you a password reset link. Please check your email.");
    }
}
