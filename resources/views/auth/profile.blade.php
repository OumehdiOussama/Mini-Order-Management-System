@extends('layouts.app')
@section('title', 'Profile & Settings')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Manage your account preferences and security</p>
    </div>
</div>

<div x-data="{ 
    tab: 'profile',
    photoPreview: null,
    handlePhotoChange(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.photoPreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}" class="grid grid-cols-1 lg:grid-cols-4 gap-7">

    {{-- Left Sidebar — Tab Navigation --}}
    <div class="lg:col-span-1">
        <div class="card p-2 space-y-1">
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
        </div>

        <div class="mt-6 card p-5 bg-gradient-to-br from-brand-500/10 to-violet-500/10 border-brand-100 dark:border-brand-900/20">
            <h3 class="text-sm font-bold text-brand-700 dark:text-brand-300">Need Help?</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                If you have questions about your account settings, please contact our support team.
            </p>
            <button class="mt-4 text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">
                View Documentation →
            </button>
        </div>
    </div>

    {{-- Right Content --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- PROFILE TAB --}}
        <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card overflow-hidden">
            {{-- Profile Header/Banner --}}
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden ring-4 ring-white dark:ring-slate-900 shadow-xl transition-transform duration-300 group-hover:scale-105">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!photoPreview">
                                @if(auth()->user()->photo)
                                    <img src="{{ url('media/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white" 
                                         style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                            </template>
                        </div>
                        <label for="photo-upload" class="absolute -bottom-2 -right-2 w-9 h-9 bg-brand-500 text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-brand-600 hover:scale-110 transition-all duration-200 border-2 border-white dark:border-slate-900">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">{{ auth()->user()->name }}</h2>
                        <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-2.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-400 border border-brand-100 dark:border-brand-800">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                            <span class="text-xs text-slate-400 flex items-center gap-1.5 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Account active since {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ url('/profile') }}" method="POST" enctype="multipart/form-data" class="space-y-7">
                    @csrf
                    @method('PUT')

                    <input type="file" id="photo-upload" name="photo" class="hidden" accept="image/*" @change="handlePhotoChange">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="form-group">
                            <label class="input-label">Full Name</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="input-field pl-10" placeholder="e.g. John Doe">
                            </div>
                            @error('name') <span class="input-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                       class="input-field pl-10" placeholder="e.g. john@example.com">
                            </div>
                            @error('email') <span class="input-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label">Phone Number</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       class="input-field pl-10" placeholder="e.g. 0612345678">
                            </div>
                            @error('phone') <span class="input-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label">Account Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input type="text" value="{{ ucfirst(auth()->user()->role) }}" 
                                       class="input-field pl-10 bg-slate-50/50 dark:bg-slate-800/50 text-slate-500 cursor-not-allowed" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="reset" class="btn-secondary px-6">Discard</button>
                        <button type="submit" class="btn-primary px-8 shadow-brand-500/20 shadow-lg">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SECURITY TAB --}}
        <div x-show="tab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card overflow-hidden" style="display: none;">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Security Settings</h2>
                <p class="text-sm text-slate-400 mt-1">Keep your account secure by updating your password regularly</p>
            </div>

            <div class="p-8">
                <form action="{{ url('/change-password') }}" method="POST" class="max-w-xl space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="input-label">Current Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="current_password" class="input-field pl-10" placeholder="••••••••">
                        </div>
                        @error('current_password') <span class="input-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="input-label">New Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5 0V7a2 2 0 00-2-2 2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="password" name="new_password" class="input-field pl-10" placeholder="••••••••">
                            </div>
                            @error('new_password') <span class="input-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="input-label">Confirm Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-200 text-slate-400 group-focus-within:text-brand-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input type="password" name="new_password_confirmation" class="input-field pl-10" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" class="btn-primary px-8 shadow-brand-500/20 shadow-lg">
                            Update Password
                        </button>
                        <p class="text-xs text-slate-400 font-medium">
                            <svg class="w-3 h-3 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            After updating, you will stay logged in.
                        </p>
                    </div>
                </form>
            </div>
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
             class="card overflow-hidden" style="display: none;">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Notification Preferences</h2>
                <p class="text-sm text-slate-400 mt-1">Decide how and when you want to be notified</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div @click="updateSetting('email')" class="group flex items-center justify-between p-5 border border-slate-100 dark:border-slate-800 rounded-2xl cursor-pointer hover:border-brand-300 dark:hover:border-brand-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">Email Updates</p>
                                <p class="text-xs text-slate-400">Order reports & news</p>
                            </div>
                        </div>
                        <div :class="settings.email ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-12 h-6.5 rounded-full relative transition-colors duration-200">
                            <div :class="settings.email ? 'translate-x-5.5' : 'translate-x-0'" class="absolute left-1 top-1 w-4.5 h-4.5 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                        </div>
                    </div>

                    <div @click="updateSetting('in_app')" class="group flex items-center justify-between p-5 border border-slate-100 dark:border-slate-800 rounded-2xl cursor-pointer hover:border-brand-300 dark:hover:border-brand-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">In-App Alerts</p>
                                <p class="text-xs text-slate-400">Real-time indicators</p>
                            </div>
                        </div>
                        <div :class="settings.in_app ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-12 h-6.5 rounded-full relative transition-colors duration-200">
                            <div :class="settings.in_app ? 'translate-x-5.5' : 'translate-x-0'" class="absolute left-1 top-1 w-4.5 h-4.5 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                        </div>
                    </div>

                    <div @click="updateSetting('sms')" class="group flex items-center justify-between p-5 border border-slate-100 dark:border-slate-800 rounded-2xl cursor-pointer hover:border-brand-300 dark:hover:border-brand-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">SMS Alerts</p>
                                <p class="text-xs text-slate-400">Urgent mobile pings</p>
                            </div>
                        </div>
                        <div :class="settings.sms ? 'bg-brand-500' : 'bg-slate-200 dark:bg-slate-700'" class="w-12 h-6.5 rounded-full relative transition-colors duration-200">
                            <div :class="settings.sms ? 'translate-x-5.5' : 'translate-x-0'" class="absolute left-1 top-1 w-4.5 h-4.5 bg-white rounded-full transition-transform duration-200 shadow-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection