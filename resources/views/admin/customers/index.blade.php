@extends('layouts.app')
@section('title', 'Customers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customers</h1>
            <p class="page-subtitle">Manage your customer base</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Customer
        </a>
    </div>

    {{-- Filter/Search Bar --}}
    <div class="card px-4 py-3 mb-5">
        <form method="GET" action="{{ route('customers.index') }}" class="flex items-end gap-3">
            <div class="flex-1">
                <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or email…"
                        class="input-field pl-9 h-10">
                </div>
            </div>

            <div class="w-64">
                <label class="text-[10px] font-bold uppercase text-slate-400 mb-1 block ml-1">Sort By</label>
                <select name="sort" class="select-field h-10" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Member</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest Member</option>
                    <option value="spent_high" {{ request('sort') === 'spent_high' ? 'selected' : '' }}>Total Spent (High to Low)</option>
                    <option value="spent_low" {{ request('sort') === 'spent_low' ? 'selected' : '' }}>Total Spent (Low to High)</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[10px] mb-1 block">&nbsp;</span>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary h-10 px-5 flex items-center gap-2 shadow-sm shadow-brand-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>
                    
                    @if(request()->anyFilled(['search', 'sort']))
                    <a href="{{ route('customers.index') }}" class="btn-ghost h-10 px-4 flex items-center gap-2 text-slate-500 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Clear</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if($customers->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="empty-state-title">No customers found</p>
                <p class="empty-state-desc">
                    {{ request('search') ? 'Try adjusting your search' : 'Add your first customer to get started' }}
                </p>
                <a href="{{ route('customers.create') }}" class="btn-primary btn-sm">+ New Customer</a>
            </div>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Member Since</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($customer->user && $customer->user->photo)
                                            <img src="{{ url('media/' . $customer->user->photo) }}" 
                                                 alt="{{ $customer->name }}" 
                                                 class="shrink-0 object-cover border border-slate-200 dark:border-slate-700"
                                                 style="width:34px;height:34px;border-radius:10px;">
                                        @else
                                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;font-weight:700;shrink:0">
                                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('customers.show', $customer) }}"
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400">
                                                {{ $customer->name }}
                                            </a>
                                            <p class="text-xs text-slate-400">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-600 dark:text-slate-300">{{ $customer->phone }}</td>
                                <td>
                                    <span class="badge-default">{{ $customer->orders_count }} orders</span>
                                </td>
                                <td class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ number_format($customer->total_spent ?? 0, 2) }} MAD
                                </td>
                                <td class="text-xs text-slate-400">{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('customers.show', $customer) }}"
                                        title="View"
                                        class="btn-icon text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}"
                                        title="Edit"
                                        class="btn-icon text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm-delete="customer" title="Delete"
                                                    class="btn-icon text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @if($customers->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-700">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
    @endif

@endsection