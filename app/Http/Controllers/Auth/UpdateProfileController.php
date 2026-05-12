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
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            
            $path = $request->file('photo')->store('avatars', 'public');
            $data['photo'] = $path;
        }

        $user->update($data);

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
