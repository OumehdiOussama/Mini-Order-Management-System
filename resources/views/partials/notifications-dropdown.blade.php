{{-- ══════════════════════════════════════════
    NOTIFICATIONS DROPDOWN
    Works with Laravel's database notifications
    auth()->user()->unreadNotifications
══════════════════════════════════════════ --}}
@auth
<div x-data="{
        open: false,
        notifications: [],
        unreadCount: 0,
        loading: false,
        init() {
            this.fetchNotifications();
        },
        async fetchNotifications() {
            // Reads from meta tag rendered by Laravel
            const el = document.getElementById('notif-data');
            if (el) {
                try {
                    this.notifications = JSON.parse(el.textContent) || [];
                    this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                } catch(e) { this.notifications = []; }
            }
        },
        async markAllRead() {
            try {
                await fetch('{{ route('notifications.markAllRead') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    }
                });
                this.notifications = this.notifications.map(n => ({ ...n, read_at: new Date().toISOString() }));
                this.unreadCount = 0;
            } catch(e) {}
        },
        async markRead(id) {
            try {
                await fetch(`/notifications/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    }
                });
                const notif = this.notifications.find(n => n.id === id);
                if (notif && !notif.read_at) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch(e) {}
        },
        timeAgo(dateStr) {
            const now = new Date(), d = new Date(dateStr);
            const diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'just now';
            if (diff < 3600) return Math.floor(diff/60) + 'm ago';
            if (diff < 86400) return Math.floor(diff/3600) + 'h ago';
            return Math.floor(diff/86400) + 'd ago';
        }
     }" class="relative">

    {{-- Bell Button --}}
    <button @click="open = !open; if(open) fetchNotifications()"
            class="relative btn-icon text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-0">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        {{-- Unread badge --}}
        <span x-show="unreadCount > 0" x-cloak
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none shadow-sm border border-white dark:border-slate-900">
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         @click.outside="open = false"
         class="absolute right-0 top-full mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-dropdown border border-slate-200 dark:border-slate-700 z-[100] overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-800 dark:text-white">Notifications</span>
                <span x-show="unreadCount > 0"
                      class="px-2 py-0.5 bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 text-[10px] font-bold rounded-full"
                      x-text="unreadCount + ' new'">
                </span>
            </div>
            <div class="flex items-center gap-3">
                <button x-show="loading" class="animate-spin text-slate-400">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="3" d="M12 4V2m0 20v-2m10-8h-2M4 12H2m15.364-6.364l-1.414 1.414M6.343 17.657l-1.414 1.414m12.728 0l-1.414-1.414M6.343 6.343L4.929 4.929" /></svg>
                </button>
                <button x-show="unreadCount > 0" x-cloak
                        @click="markAllRead()"
                        class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
                    Mark all read
                </button>
            </div>
        </div>

        {{-- Notification List --}}
        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            <template x-if="!loading && notifications.length === 0">
                <div class="empty-state py-10">
                    <div class="empty-state-icon mx-auto">
                        <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No notifications yet</p>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <div @click="markRead(notif.id)" class="flex gap-4 px-4 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all border-b border-slate-50 dark:border-slate-700/50 cursor-pointer relative overflow-hidden"
                     :class="!notif.read_at ? 'bg-brand-50/30 dark:bg-brand-900/10' : ''">
                    
                    {{-- Avatar / Icon --}}
                    <div class="shrink-0">
                        <template x-if="notif.data?.actor_photo">
                            <img :src="'{{ url('media') }}/' + notif.data.actor_photo" 
                                 class="w-10 h-10 rounded-xl object-cover ring-2 ring-white dark:ring-slate-800 shadow-sm">
                        </template>
                        <template x-if="!notif.data?.actor_photo">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                                 :class="{
                                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400': notif.data?.type === 'success',
                                    'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400': notif.data?.type === 'info' || !notif.data?.type,
                                    'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400': notif.data?.type === 'warning',
                                    'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': notif.data?.type === 'error',
                                 }">
                                <template x-if="notif.data?.actor_name">
                                    <span class="text-xs font-bold" x-text="notif.data.actor_name.substring(0,2).toUpperCase()"></span>
                                </template>
                                <template x-if="!notif.data?.actor_name">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                    </svg>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-[13px] font-bold text-slate-900 dark:text-white leading-tight"
                               x-text="notif.data?.title || 'Notification'"></p>
                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap"
                                  x-text="timeAgo(notif.created_at)"></span>
                        </div>
                        <p class="text-[12px] text-slate-600 dark:text-slate-300 mt-1 line-clamp-2 leading-relaxed"
                           x-html="notif.data?.message || ''"></p>
                        
                        {{-- Context Badge (Amount/Status) --}}
                        <template x-if="notif.data?.amount || notif.data?.status">
                            <div class="mt-2 flex items-center gap-2">
                                <template x-if="notif.data?.amount">
                                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded-md border border-emerald-100 dark:border-emerald-800" 
                                          x-text="notif.data.amount"></span>
                                </template>
                                <template x-if="notif.data?.status">
                                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:bg-blue-400 text-[10px] font-bold rounded-md border border-blue-100 dark:border-blue-800" 
                                          x-text="notif.data.status.toUpperCase()"></span>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Unread dot --}}
                    <div x-show="!notif.read_at"
                         class="absolute top-4 right-4 w-2 h-2 bg-brand-500 rounded-full shrink-0 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-700 text-center">
            <a href="{{ route('dashboard.index') }}"
               class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
                View all notifications →
            </a>
        </div>
    </div>

</div>

@php
    $notifCacheKey = 'user_notifications_' . auth()->id();
    $notificationsData = cache()->remember($notifCacheKey, 60, function () {
        return auth()->user()->notifications()
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at,
            ]);
    });
@endphp
<script id="notif-data" type="application/json">
    @json($notificationsData)
</script>
@endauth
