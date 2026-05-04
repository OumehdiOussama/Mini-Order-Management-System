<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __invoke(UpdateProfileRequest $request)
    {
        $user = User::find(Auth::id());
        $user->update($request->validated());

        if ($user->role === 'customer' && $user->customer) {
            $user->customer->update([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
