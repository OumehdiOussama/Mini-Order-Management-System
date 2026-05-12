<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyAccountRequest;
use App\Models\User;

class VerifyAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(VerifyAccountRequest $request)
    {
        $type = filter_var($request->input('identifier'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $user = User::where($type, $request->identifier)->first();
        
        if (!$user) {
            return back()->with("error", "User not found or session expired.");
        }

        if($user->otp != implode("",$request->otp)){
            return back()->with("error","Invalid OTP code. Please try again.");
        }

        $user->account_verified_at = now();
        $user->save();

        return redirect()->route("login")->with("success", "Your account verified successfully, you can login now");
    }
}
