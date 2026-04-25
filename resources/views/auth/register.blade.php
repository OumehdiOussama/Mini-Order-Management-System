@extends("layouts.auth")

@section("title", "Register")

@section("content")

<div class="w-full max-w-sm p-6 pb-3 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">

    <h2 class="text-2xl font-bold text-center pb-7">Create account</h2>

    <form action="{{ url('/register') }}" method="POST" class="space-y-4">
        @csrf

        <div class="space-y-1.5">

            <!-- Name -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Name</label>

                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("name")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Phone</label>

                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("phone")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Email</label>

                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full p-2.5 text-sm text-gray-100 bg-gray-700 border rounded focus:outline-none focus:ring-2 border-gray-600 focus:ring-blue-500">

                @error("email")
                    <span class="block mt-1 text-xs text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-0.5 text-sm text-gray-300">Password</label>

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
            class="w-full py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            Register
        </button>

        <!-- Login -->
        <p class="mt-3 text-xs text-center text-gray-400">
            Already have an account ?
            <a href="{{ route('login') }}" class="text-blue-400 hover:underline">
                Login
            </a>
        </p>

    </form>

</div>

@endsection