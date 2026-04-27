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
    <button @click="open = !open"
            class="relative btn-icon text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        {{-- Unread badge --}}
        <span x-show="unreadCount > 0"
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         @click.outside="open = false"
         class="absolute right-0 top-full mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-dropdown border border-slate-200 dark:border-slate-700 z-50 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-800 dark:text-white">Notifications</span>
                <span x-show="unreadCount > 0"
                      class="badge-processing text-[10px] px-2 py-0.5"
                      x-text="unreadCount + ' new'">
                </span>
            </div>
            <button x-show="unreadCount > 0"
                    @click="markAllRead()"
                    class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
                Mark all read
            </button>
        </div>

        {{-- Notification List --}}
        <div class="max-h-72 overflow-y-auto">
            <template x-if="notifications.length === 0">
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
                <div class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700/50 cursor-pointer"
                     :class="!notif.read_at ? 'bg-brand-50/50 dark:bg-brand-900/10' : ''">
                    {{-- Type Icon --}}
                    <div class="shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             :class="{
                                'bg-emerald-100 dark:bg-emerald-900/30': notif.data?.type === 'success',
                                'bg-blue-100 dark:bg-blue-900/30': notif.data?.type === 'info' || !notif.data?.type,
                                'bg-amber-100 dark:bg-amber-900/30': notif.data?.type === 'warning',
                                'bg-red-100 dark:bg-red-900/30': notif.data?.type === 'error',
                             }">
                            <svg class="w-4 h-4"
                                 :class="{
                                    'text-emerald-600': notif.data?.type === 'success',
                                    'text-blue-600': notif.data?.type === 'info' || !notif.data?.type,
                                    'text-amber-600': notif.data?.type === 'warning',
                                    'text-red-600': notif.data?.type === 'error',
                                 }"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 leading-snug"
                           x-text="notif.data?.title || 'Notification'"></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2"
                           x-text="notif.data?.message || ''"></p>
                        <p class="text-[10px] text-slate-400 mt-1"
                           x-text="timeAgo(notif.created_at)"></p>
                    </div>
                    {{-- Unread dot --}}
                    <div x-show="!notif.read_at"
                         class="w-2 h-2 bg-brand-500 rounded-full mt-1.5 shrink-0"></div>
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
    $notificationsData = auth()->user()->notifications()
        ->latest()
        ->take(15)
        ->get()
        ->map(function($n) {
            return [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at,
            ];
        });
@endphp
<script id="notif-data" type="application/json">
    @json($notificationsData)
</script>
@endauth
