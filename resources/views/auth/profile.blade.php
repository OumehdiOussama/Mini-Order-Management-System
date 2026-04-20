@extends("layouts.app")

@section("title", "Profile")

@section("content")

<div class="mb-8">
    <h1 class="mb-2 text-4xl font-bold text-gray-800">Settings</h1>
    <p class="text-gray-600">Manage your account settings</p>
</div>

@if(session("success"))
    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
        {{ session("success") }}
    </div>
@endif
@if(session("error"))
    <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
        {{ session("error") }}
    </div>
@endif

<!-- Tabs -->
<div class="mb-6 bg-white rounded-lg shadow-md">
    <div class="flex ">
        <button onclick="showTab('profileTab', this)" 
            class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600 tab-btn hover:text-blue-600">
            Profile
        </button>

        <button onclick="showTab('passwordTab', this)" 
            class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600 tab-btn hover:text-blue-600">
            Change Password
        </button>
    </div>
</div>

<!-- Content -->
<div class="p-6 bg-white rounded-lg shadow-md">

    <!-- Profile Tab -->
    <div id="profileTab" class="tab-content">
        <h2 class="mb-4 text-xl font-bold text-gray-800">Update Profile</h2>

        <form method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 text-gray-600">Name</label>
                <input type="text" name="name"
                    value="{{ auth()->user()->name }}"
                    class="w-full p-3 transition border-2 border-gray-300 rounded focus:border-blue-600 focus:outline-none">
                @error('name')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-gray-600">Email</label>
                <input type="email" name="email"
                    value="{{ auth()->user()->email }}"
                    class="w-full p-3 transition border-2 border-gray-300 rounded focus:border-blue-600 focus:outline-none">
                @error('email')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <button class="px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Save Changes
            </button>
        </form>
    </div>

    <!-- Password Tab -->
    <div id="passwordTab" class="hidden tab-content">
        <h2 class="mb-4 text-xl font-bold text-gray-800">Change Password</h2>

        <form action="{{ url("/change-password") }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 text-gray-600">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full p-3 transition border-2 border-gray-300 rounded focus:border-blue-600 focus:outline-none">
                @error('current_password')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-gray-600">New Password</label>
                <input type="password" name="new_password"
                    class="w-full p-3 transition border-2 border-gray-300 rounded focus:border-blue-600 focus:outline-none">
                @error('new_password')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-gray-600">Confirm Password</label>
                <input type="password" name="new_password_confirmation"
                    class="w-full p-3 transition border-2 border-gray-300 rounded focus:border-blue-600 focus:outline-none">
            </div>

            <button class="px-5 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                Update Password
            </button>
        </form>
    </div>

</div>

<!-- Script -->
<script>
function showTab(tabId, el) {

    // hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // show selected tab
    document.getElementById(tabId).classList.remove('hidden');

    // reset all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('text-gray-600', 'border-transparent');
    });

    // activate clicked button
    el.classList.add('border-blue-600', 'text-blue-600');
    el.classList.remove('text-gray-600', 'border-transparent');
}

// default tab
window.onload = () => {
    showTab('profileTab', document.querySelector('.tab-btn'));
}
</script>

@endsection