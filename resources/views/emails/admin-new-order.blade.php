@extends('layouts.email')

@section('subject', 'New Order Received - #' . $order->id)

@section('content')
    <h1 class="title">New Order Received</h1>

    <p class="text">
        A new order has been placed by <strong>{{ $order->customer->name ?? 'Guest' }}</strong>.
    </p>

    <table style="width:100%; margin-bottom: 18px;">
        <tr>
            <td style="vertical-align: top;">
                <p class="label">Customer</p>
                <p class="value">{{ $order->customer->name ?? 'Guest' }}<br>{{ $order->customer->email ?? 'No email' }}</p>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <p class="label">Contact</p>
                <p class="value">{{ $order->customer->phone ?? 'N/A' }}</p>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 12px;">
                <p class="label">Order ID</p>
                <p class="value">#{{ $order->id }}</p>
            </td>
            <td style="vertical-align: top; text-align: right; padding-top: 12px;">
                <p class="label">Date / Status</p>
                <p class="value">{{ $order->created_at->format('M d, Y H:i') }} / {{ ucfirst($order->status) }}</p>
            </td>
        </tr>
    </table>

    <div style="background: #f6f9fc; padding: 12px; border-radius: 6px; margin-bottom: 18px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #e6ebf1;">
                    <th style="padding: 8px 0; width: 60%;">Item</th>
                    <th style="padding: 8px 0; width: 20%; text-align: center;">Qty</th>
                    <th style="padding: 8px 0; width: 20%; text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                    <tr style="border-bottom: 1px solid #f0f2f5;">
                        <td style="padding: 10px 0;">{{ $product->name }}</td>
                        <td style="padding: 10px 0; text-align: center;">{{ $product->pivot->quantity }}</td>
                        <td style="padding: 10px 0; text-align: right;">{{ number_format($product->price * $product->pivot->quantity, 2) }} MAD</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding-top: 12px;" colspan="2"><p class="label">Total Amount</p></td>
                    <td style="padding-top: 12px; text-align: right;"><p class="value" style="font-size:18px; font-weight:700;">{{ number_format($order->getTotalPrice(), 2) }} MAD</p></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <p class="label">Verification Plan</p>
        <p class="text">Please follow the verification checklist below before approving the order for fulfillment.</p>
        <ul style="color: #525f7f; font-size: 14px;">
            <li>Confirm customer identity and contact details.</li>
            <li>Verify payment or payment terms.</li>
            <li>Check stock availability for each item.</li>
        </ul>
    </div>

    <div style="margin-bottom: 18px;">
        <p class="label">Manual Verification</p>
        <p class="text">If any item requires manual approval, please review the order details and mark the order accordingly in the admin panel.</p>
    </div>

    <div style="text-align: center; margin-top: 28px;">
        <a href="{{ url('/orders/' . $order->id) }}" class="btn">Open Order</a>
    </div>
@endsection
