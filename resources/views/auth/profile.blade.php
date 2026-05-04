@extends('layouts.app')
@section('title', 'Profile & Settings')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Manage your account preferences and security</p>
    </div>
</div>

<div x-data="{ tab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-4 gap-7">

    {{-- Left Sidebar — Tab Navigation --}}
    <div class="lg:col-span-1">
        <nav class="space-y-1">
            <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My Profile
            </button>
            <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Security & Password
            </button>
            <button @click="tab = 'notifications'"
                    :class="tab === 'notifications' ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
            </button>
        </nav>
    </div>

    {{-- Right Content --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- PROFILE TAB --}}
        <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card p-6">
            <div class="flex items-center gap-4 mb-8">
                <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:24px;color:white;font-weight:700;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-400">Manage your personal information</p>
                </div>
            </div>

            <form action="{{ url('/profile') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="input-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-field">
                        @error('name') <span class="input-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="input-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input-field">
                        @error('email') <span class="input-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="input-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="input-field">
                        @error('phone') <span class="input-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="input-label">Role</label>
                        <input type="text" value="{{ ucfirst(auth()->user()->role) }}" class="input-field bg-slate-50 dark:bg-slate-800/50 text-slate-400" disabled>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="btn-primary">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- SECURITY TAB --}}
        <div x-show="tab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card p-6" style="display: none;">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Change Password</h2>
                <p class="text-sm text-slate-400">Update your account password for better security</p>
            </div>

            <form action="{{ url('/change-password') }}" method="POST" class="space-y-5 max-w-lg">
                @csrf
                <div class="form-group">
                    <label class="input-label">Current Password</label>
                    <input type="password" name="current_password" class="input-field" placeholder="••••••••">
                    @error('current_password') <span class="input-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="input-label">New Password</label>
                    <input type="password" name="new_password" class="input-field" placeholder="Minimum 8 characters">
                    @error('new_password') <span class="input-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="input-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="input-field" placeholder="Repeat new password">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- NOTIFICATIONS TAB --}}
        <div x-show="tab === 'notifications'" 
             x-data="{ 
                settings: {{ json_encode(auth()->user()->notification_settings ?? ['email' => true, 'in_app' => true, 'sms' => false]) }},
                async updateSetting(key) {
                    this.settings[key] = !this.settings[key];
                    try {
                        const response = await fetch('{{ route('profile.notifications.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.settings)
                        });
                        if (!response.ok) throw new Error('Failed to update');
                    } catch (e) {
                        console.error(e);
                        this.settings[key] = !this.settings[key]; // Revert on failure
                    }
                }
             }"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="card p-6" style="display: none;">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Notification Settings</h2>
                <p class="text-sm text-slate-400">Manage how you receive alerts and updates (Changes are saved automatically)</p>
            </div>

            <div class="space-y-4">
                <div @click="updateSetting('email')" class="flex items-center justify-between p-4 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Email Notifications</p>
                        <p class="text-xs text-slate-400">Receive order updates and reports via email</p>
                    </div>
                    <div :class="settings.email ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-10 h-6 rounded-full relative transition-colors duration-200">
                        <div :class="settings.email ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                    </div>
                </div>

                <div @click="updateSetting('in_app')" class="flex items-center justify-between p-4 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">In-App Alerts</p>
                        <p class="text-xs text-slate-400">Show notification bell indicator for new events</p>
                    </div>
                    <div :class="settings.in_app ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-10 h-6 rounded-full relative transition-colors duration-200">
                        <div :class="settings.in_app ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                    </div>
                </div>

                <div @click="updateSetting('sms')" class="flex items-center justify-between p-4 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">SMS Notifications</p>
                        <p class="text-xs text-slate-400">Receive urgent alerts on your mobile phone</p>
                    </div>
                    <div :class="settings.sms ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-10 h-6 rounded-full relative transition-colors duration-200">
                        <div :class="settings.sms ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection