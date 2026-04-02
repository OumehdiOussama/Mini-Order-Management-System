@extends('layouts.app')

@section('title', 'Update Order #' . $order->id)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Update Order #{{ $order->id }}</h1>

            @php
                $selectedStatus = old('status', $order->status);
            @endphp

            <form action="{{ route('orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Current Order Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded border border-gray-200">
                    <h3 class="font-semibold mb-2">Order Information</h3>
                    <p class="text-gray-700"><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                    <p class="text-gray-700"><strong>Items:</strong> {{ $order->products->count() }} product(s)</p>
                    <p class="text-gray-700"><strong>Total:</strong> {{ number_format($order->getTotalPrice(), 2) }} MAD</p>
                </div>

                <!-- Status Update -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">New Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('status') border-red-500! focus:border-red-500! @enderror">
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('notes') border-red-500! focus:border-red-500! @enderror" placeholder="Add any notes about this status change...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shipping Info (only when updating to shipped status) -->
                <div id="shippingInfo" class="mb-6 p-4 bg-blue-50 rounded border border-blue-200" style="display: {{ $selectedStatus === 'shipped' ? 'block' : 'none' }};">
                    <h3 class="font-semibold mb-3 text-blue-900">Shipping Information</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Tracking Number <span class="text-red-500">*</span></label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('tracking_number') border-red-500! focus:border-red-500! @enderror" placeholder="e.g., TRK123456789">
                        @error('tracking_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Carrier <span class="text-red-500">*</span></label>
                        <select name="carrier" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500 border-gray-300 @error('carrier') border-red-500! focus:border-red-500! @enderror">
                            <option value="">-- Select Carrier --</option>
                            <option value="FedEx" {{ old('carrier', $order->carrier) === 'FedEx' ? 'selected' : '' }}>FedEx</option>
                            <option value="UPS" {{ old('carrier', $order->carrier) === 'UPS' ? 'selected' : '' }}>UPS</option>
                            <option value="DHL" {{ old('carrier', $order->carrier) === 'DHL' ? 'selected' : '' }}>DHL</option>
                            <option value="USPS" {{ old('carrier', $order->carrier) === 'USPS' ? 'selected' : '' }}>USPS</option>
                            <option value="PostNL" {{ old('carrier', $order->carrier) === 'PostNL' ? 'selected' : '' }}>PostNL</option>
                            <option value="DPD" {{ old('carrier', $order->carrier) === 'DPD' ? 'selected' : '' }}>DPD</option>
                            <option value="Other" {{ old('carrier', $order->carrier) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('carrier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Update Order Status</button>
                    <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded font-semibold hover:bg-gray-700 text-center">Cancel</a>
                </div>
            </form>

            <script>
                const statusSelect = document.querySelector('select[name="status"]');
                const shippingInfo = document.getElementById('shippingInfo');
                const trackingInput = document.querySelector('input[name="tracking_number"]');
                const carrierSelect = document.querySelector('select[name="carrier"]');

                function toggleShippingFields() {
                    const selectedStatus = statusSelect.value;
                    const showShippingInfo = selectedStatus === 'shipped';

                    shippingInfo.style.display = showShippingInfo ? 'block' : 'none';
                    trackingInput.required = showShippingInfo;
                    carrierSelect.required = showShippingInfo;
                }

                toggleShippingFields();
                statusSelect.addEventListener('change', toggleShippingFields);
            </script>
        </div>
    </div>
@endsection
