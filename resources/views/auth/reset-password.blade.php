@extends("layouts.auth")

@section("title", "Reset Password")

@section("content")

<div class="w-full max-w-sm p-6 pb-3 space-y-4 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">

    <h2 class="pb-3 text-2xl font-bold text-center">Reset Password</h2>

    @if(session("success"))
        <div class="p-2 text-sm text-center text-green-400 rounded bg-green-900/30">
            {{ session("success") }}
        </div>
    @endif
    
    @if(session("error"))
        <div class="p-2 text-sm text-center text-red-400 rounded bg-red-900/30">
            {{ session("error") }}
        </div>
    @endif

    <form action="{{ route("password.update") }}" method="POST" class="space-y-3">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-1.5">

            <!-- Email -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Email</label>

                <input type="email" name="email" value="{{ old("email") }}"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("email")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">New Password</label>

                <input type="password" name="password"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("password")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Confirm Password</label>

                <input type="password" name="password_confirmation"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("password_confirmation")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

        </div>

        <!-- Button -->
        <button type="submit"
            class="w-full py-2.5 mt-1 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            Reset Password
        </button>

        <!-- Back to Login -->
        <p class="mt-3 text-xs text-center text-gray-400">
            Remember your password ?
            <a href="{{ route("login") }}" class="text-blue-400 hover:underline">
                Login
            </a>
        </p>

    </form>

</div>

@endsection