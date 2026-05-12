@extends('layouts.email')

@section('subject', 'Update for order #' . $order->id)

@section('content')
    <h1 class="title">Your order progress has been updated</h1>
    <p class="text">
        Hello {{ explode(' ', $order->customer->name)[0] }}, <br><br>
        We’re writing to keep you informed about the progress of your order <strong>#{{ $order->id }}</strong>. Our team has just updated your status:
    </p>

    <div style="text-align: center; margin: 32px 0;">
        <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : 'badge-info' }}" style="padding: 12px 24px; font-size: 16px;">
            {{ strtoupper($order->status) }}
        </span>
    </div>

    @if($order->status === 'shipped')
        <div style="border: 1px solid #e6ebf1; border-radius: 8px; padding: 24px; margin-bottom: 32px;">
            <p class="label" style="margin-bottom: 12px;">Shipment Tracking</p>
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 14px; color: #525f7f; padding-bottom: 8px;">Carrier</td>
                    <td style="font-size: 14px; font-weight: 600; text-align: right; padding-bottom: 8px;">{{ $order->carrier ?? 'Standard Shipping' }}</td>
                </tr>
                <tr>
                    <td style="font-size: 14px; color: #525f7f;">Tracking Number</td>
                    <td style="font-size: 14px; font-weight: 600; text-align: right; color: #6366f1;">{{ $order->tracking_number ?? 'TBA' }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div style="text-align: center; margin-bottom: 32px;">
        <a href="{{ url('/orders/' . $order->id) }}" class="btn">Track Order Progress</a>
    </div>

    <p class="text" style="font-size: 14px; color: #8898aa;">
        We'll continue to update you as your order moves through the processing stages.
    </p>
@endsection

@section('accent')
    <table style="width: 100%;">
        <tr>
            <td style="vertical-align: top;">
                <p class="label">Questions?</p>
                <p class="value" style="font-size: 13px; margin: 0;">Reply to this email or visit support.</p>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <p class="label">Order Summary</p>
                <p class="value" style="font-size: 13px; margin: 0;">Total: {{ number_format($order->getTotalPrice(), 2) }} MAD</p>
            </td>
        </tr>
    </table>
@endsection
