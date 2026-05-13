@extends('layouts.app')

@section('title', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

<div x-data="dashboardMetrics">

{{-- ══════════ PAGE HEADER ══════════ --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ auth()->user()->name ?? 'there' }}
        </h1>
        <p class="page-subtitle">{{ now()->format('l, F j, Y') }} · Here's what's happening today</p>
    </div>
    <div class="flex items-center gap-3">
        <template x-if="loading">
            <span class="flex items-center gap-2 text-xs text-slate-400">
                <svg class="animate-spin h-3 w-3 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Syncing live data...
            </span>
        </template>
        <a href="{{ route('orders.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Order
        </a>
    </div>
</div>

{{-- ══════════ KPI STAT CARDS ══════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

    {{-- Total Orders --}}
    <div class="stat-card">
        <div class="stat-icon bg-brand-100 dark:bg-brand-900/30">
            <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Total Orders</p>
            <p class="stat-value">
                <span x-text="formatNumber(stats.totalOrders)">{{ number_format($totalOrders) }}</span>
            </p>
            <p class="stat-trend text-emerald-600 dark:text-emerald-400">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                All time
            </p>
        </div>
    </div>

    @if(auth()->user()->role !== 'customer')
    {{-- Total Revenue --}}
    <div class="stat-card">
        <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/30">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value text-emerald-600 dark:text-emerald-400">
                <span x-text="formatNumber(stats.revenue)">{{ number_format($totalRevenue, 0) }}</span>
                <span class="text-sm font-medium ml-0.5">MAD</span>
            </p>
            <p class="stat-trend text-slate-400">Excluding cancelled</p>
        </div>
    </div>

    {{-- Total Customers --}}
    <div class="stat-card">
        <div class="stat-icon bg-violet-100 dark:bg-violet-900/30">
            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Total Customers</p>
            <p class="stat-value">
                <span x-text="formatNumber(stats.customers)">{{ number_format($totalCustomers ?? 0) }}</span>
            </p>
            <p class="stat-trend text-slate-400">Registered accounts</p>
        </div>
    </div>
    @else
    {{-- Total Spent (Customer) --}}
    <div class="stat-card">
        <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/30">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Total Spent</p>
            <p class="stat-value text-emerald-600 dark:text-emerald-400">
                <span x-text="formatNumber(stats.revenue)">{{ number_format($totalSpent, 0) }}</span>
                <span class="text-sm font-medium ml-0.5">MAD</span>
            </p>
            <p class="stat-trend text-slate-400">Excluding cancelled</p>
        </div>
    </div>
    @endif

    {{-- Delivered Orders --}}
    <div class="stat-card">
        <div class="stat-icon bg-amber-100 dark:bg-amber-900/30">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Delivered</p>
            <p class="stat-value">
                <span x-text="formatNumber(stats.ordersByStatus.delivered)">{{ number_format($ordersByStatus['delivered']) }}</span>
            </p>
            <template x-if="stats.totalOrders > 0">
                <p class="stat-trend text-emerald-600 dark:text-emerald-400">
                    <span x-text="Math.round((stats.ordersByStatus.delivered / stats.totalOrders) * 100)"></span>% success rate
                </p>
            </template>
        </div>
    </div>
</div>

{{-- ══════════ STATUS OVERVIEW ══════════ --}}
<div class="card p-5 mb-7">
    <h2 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-4">
        Order Status Overview
    </h2>
    @php
        $statusConfig = [
            'pending'    => ['label' => 'Pending',    'color' => 'bg-amber-500',   'light' => 'badge-pending'],
            'processing' => ['label' => 'Processing', 'color' => 'bg-blue-500',    'light' => 'badge-processing'],
            'shipped'    => ['label' => 'Shipped',    'color' => 'bg-violet-500',  'light' => 'badge-shipped'],
            'delivered'  => ['label' => 'Delivered',  'color' => 'bg-emerald-500', 'light' => 'badge-delivered'],
            'cancelled'  => ['label' => 'Cancelled',  'color' => 'bg-red-500',     'light' => 'badge-cancelled'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
        @php $cfg = $statusConfig[$status]; @endphp
        <div class="flex flex-col items-center p-4 rounded-xl bg-slate-100 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 hover:border-brand-300 dark:hover:border-brand-600 transition-all duration-200">
            <div class="w-10 h-10 rounded-full {{ $cfg['color'] }} flex items-center justify-center mb-2 shadow-lg shadow-{{ str_replace('bg-','',$cfg['color']) }}/20">
                <span class="text-white font-bold text-sm" x-text="stats.ordersByStatus.{{ $status }}">{{ $ordersByStatus[$status] }}</span>
            </div>
            <span class="{{ $cfg['light'] }}">{{ $cfg['label'] }}</span>
            <template x-if="stats.totalOrders > 0">
                <span class="text-xs text-slate-400 mt-1" x-text="Math.round((stats.ordersByStatus.{{ $status }} / stats.totalOrders)*100) + '%'"></span>
            </template>
        </div>
        @endforeach
    </div>
</div>

{{-- ══════════ CHARTS ROW ══════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

    {{-- Line Chart: Orders last 7 days --}}
    <div class="chart-container lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Orders This Week</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daily order volume — last 7 days</p>
            </div>
            <span class="badge-info flex items-center gap-1.5">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                </span>
                Live
            </span>
        </div>
        <div class="relative h-52">
            <canvas id="ordersLineChart"></canvas>
        </div>
    </div>

    {{-- Doughnut Chart: By Status --}}
    <div class="chart-container">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">By Status</h3>
            <p class="text-xs text-slate-400 mt-0.5">Distribution of all orders</p>
        </div>
        <div class="relative h-52 flex items-center justify-center">
            <canvas id="statusDoughnutChart"></canvas>
        </div>
    </div>
</div>

{{-- ══════════ TOP PRODUCTS & ACTIVITY ══════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-7">
    
    @if(auth()->user()->role !== 'customer')
    {{-- Top Products --}}
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 uppercase tracking-wider">Top Selling Products</h3>
        @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="space-y-4">
                @foreach($topProducts as $product)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-700">
                                @if($product->image_path)
                                    <img src="{{ url('media/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                @else
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 group-hover:text-brand-600 transition-colors">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ number_format($product->price, 2) }} MAD</p>
                            </div>
                        </div>
                        <span class="badge-info">{{ $product->total_sold }} sold</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-400">No sales data yet.</p>
        @endif
    </div>

    {{-- Recent Activity --}}
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 uppercase tracking-wider">Recent Activity</h3>
        @if(isset($recentActivity) && $recentActivity->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivity as $log)
                    <div class="flex items-start gap-3 group">
                        <div class="w-2 h-2 mt-1.5 rounded-full {{ $log->action == 'created' ? 'bg-emerald-500' : ($log->action == 'deleted' ? 'bg-red-500' : 'bg-blue-500') }} group-hover:scale-125 transition-transform"></div>
                        <div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                <span class="font-bold">{{ $log->user->name ?? 'System' }}</span> 
                                {{ $log->description }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $log->created_at->diffForHumans() }} · IP: {{ $log->ip_address }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-400">No recent activity.</p>
        @endif
    </div>
    </div>
    @endif
</div>

{{-- ══════════ RECENT ORDERS TABLE ══════════ --}}
<div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700">
        <div>
            <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Recent Orders</h2>
            <p class="text-xs text-slate-400 mt-0.5">Last 5 orders placed</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn-ghost btn-sm text-brand-500 hover:text-brand-600">
            View all
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($recentOrders->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
        </div>
        <p class="empty-state-title">No orders yet</p>
        <p class="empty-state-desc">Get started by creating your first order</p>
        <a href="{{ route('orders.create') }}" class="btn-primary btn-sm">Create Order</a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td>
                        <a href="{{ route('orders.show', $order) }}"
                           class="font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="avatar-sm shrink-0"
                                 style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:11px; color:white; font-weight:700;">
                                {{ strtoupper(substr($order->customer->name ?? '?', 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->name ?? 'Deleted' }}</p>
                                <p class="text-xs text-slate-400">{{ $order->customer->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-default">{{ $order->products->count() }} items</span>
                    </td>
                    <td class="font-semibold text-slate-800 dark:text-slate-200">
                        {{ number_format($order->getTotalPrice(), 2) }} MAD
                    </td>
                    <td>
                        <span class="badge-{{ $order->status }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-slate-500 dark:text-slate-400 text-xs">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardMetrics', () => ({
        stats: {
            totalOrders: {{ $totalOrders }},
            revenue: {{ $totalRevenue ?? $totalSpent ?? 0 }},
            customers: {{ $totalCustomers ?? 0 }},
            products: {{ $totalProducts ?? 0 }},
            ordersByStatus: @json($ordersByStatus)
        },
        loading: true,
        async init() {
            try {
                const res = await fetch('{{ route('api.dashboard.metrics') }}');
                const data = await res.json();
                if (data.success) {
                    this.stats = data;
                    this.updateCharts(data);
                }
            } catch (e) { 
                console.error('Failed to load metrics', e); 
            } finally { 
                this.loading = false; 
            }
        },
        updateCharts(data) {
            if (window.ordersLineChart) {
                window.ordersLineChart.data.labels = data.chart.map(c => c.label);
                window.ordersLineChart.data.datasets[0].data = data.chart.map(c => c.count);
                window.ordersLineChart.update();
            }
            if (window.statusDoughnutChart) {
                window.statusDoughnutChart.data.datasets[0].data = Object.values(data.ordersByStatus);
                window.statusDoughnutChart.update();
            }
        },
        formatNumber(num) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            return new Intl.NumberFormat('en-US').format(num);
        }
    }));
});

(function () {

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor  = isDark ? 'rgba(51,65,85,0.6)' : 'rgba(226,232,240,0.8)';
    const textColor  = isDark ? '#94a3b8' : '#64748b';

    const lineCtx = document.getElementById('ordersLineChart');
    if (lineCtx) {
        window.ordersLineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($dayLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($ordersPerDay),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#fff',
                        titleColor: isDark ? '#f1f5f9' : '#0f172a',
                        bodyColor: isDark ? '#94a3b8' : '#64748b',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11 }, precision: 0 },
                    }
                }
            }
        });
    }

    // ── Status Doughnut ───────────────────────────────────
    const doughCtx = document.getElementById('statusDoughnutChart');
    if (doughCtx) {
        window.statusDoughnutChart = new Chart(doughCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: @json(array_values($ordersByStatus)),
                    backgroundColor: ['#f59e0b','#3b82f6','#8b5cf6','#10b981','#ef4444'],
                    borderColor: isDark ? '#1e293b' : '#fff',
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: 11 },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#fff',
                        titleColor: isDark ? '#f1f5f9' : '#0f172a',
                        bodyColor: isDark ? '#94a3b8' : '#64748b',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
})();
</script>
@endpush

