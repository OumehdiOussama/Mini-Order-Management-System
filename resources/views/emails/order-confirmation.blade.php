@extends('layouts.email')

@section('subject', 'Your order confirmation - #' . $order->id)

@section('content')
    <h1 class="title">Your order is confirmed</h1>
    <p class="text">
        Hello {{ explode(' ', $order->customer->name)[0] }}, <br><br>
        Thank you for choosing <strong>OMS</strong>. We're excited to let you know that your order has been successfully placed and is now being processed at our fulfillment center.
    </p>
    
    <p class="text" style="font-size: 15px;">
        We’ve attached the details of your purchase below. Our team is currently preparing your items for shipment, and we will send you another update with a tracking number as soon as your package leaves our facility.
    </p>

    <div style="background-color: #f6f9fc; border-radius: 8px; padding: 24px; margin: 32px 0;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding-bottom: 20px;">
                    <p class="label">Order Number</p>
                    <p class="value" style="font-size: 16px; font-weight: 700;">#{{ $order->id }}</p>
                </td>
                <td style="padding-bottom: 20px; text-align: right;">
                    <p class="label">Date Paid</p>
                    <p class="value">{{ $order->created_at->format('M d, Y') }}</p>
                </td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #e6ebf1; padding-top: 20px;">
                    <p class="value" style="font-size: 16px; font-weight: 700; margin: 0;">Total Amount</p>
                </td>
                <td style="border-top: 1px solid #e6ebf1; padding-top: 20px; text-align: right;">
                    <p class="value" style="font-size: 20px; font-weight: 800; color: #6366f1; margin: 0;">{{ number_format($order->getTotalPrice(), 2) }} MAD</p>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-bottom: 40px;">
        <a href="{{ url('/orders/' . $order->id) }}" class="btn">Track Order Details</a>
    </div>

    <div class="divider"></div>

    <h2 style="font-size: 16px; font-weight: 700; color: #32325d; margin-bottom: 12px;">What happens next?</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 10px 0; vertical-align: top; width: 30px;">
                <div style="width: 20px; height: 20px; background-color: #6366f1; border-radius: 50%; color: white; text-align: center; font-size: 12px; line-height: 20px; font-weight: bold;">1</div>
            </td>
            <td style="padding: 10px 0;">
                <p style="margin: 0; font-size: 14px; color: #525f7f;"><strong>Preparation:</strong> We are picking and packing your items with care.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0; vertical-align: top; width: 30px;">
                <div style="width: 20px; height: 20px; background-color: #6366f1; border-radius: 50%; color: white; text-align: center; font-size: 12px; line-height: 20px; font-weight: bold;">2</div>
            </td>
            <td style="padding: 10px 0;">
                <p style="margin: 0; font-size: 14px; color: #525f7f;"><strong>Shipping:</strong> You'll receive a tracking number via email once it ships.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0; vertical-align: top; width: 30px;">
                <div style="width: 20px; height: 20px; background-color: #6366f1; border-radius: 50%; color: white; text-align: center; font-size: 12px; line-height: 20px; font-weight: bold;">3</div>
            </td>
            <td style="padding: 10px 0;">
                <p style="margin: 0; font-size: 14px; color: #525f7f;"><strong>Delivery:</strong> Your order will arrive at your shipping address shortly.</p>
            </td>
        </tr>
    </table>

    <p class="text" style="font-size: 14px; color: #8898aa; margin-top: 32px; margin-bottom: 0; text-align: center;">
        Have questions about your order? <a href="mailto:support@oms.com" style="color: #6366f1; text-decoration: none;">Contact Support</a>.
    </p>
@endsection

@section('accent')
    <table style="width: 100%;">
        <tr>
            <td style="vertical-align: top;">
                <p class="label">Shipping To</p>
                <p class="value" style="font-size: 13px; line-height: 1.5; margin: 0;">
                    {{ $order->customer->name }}<br>
                    {{ $order->customer->address ?? 'Default Shipping Address' }}
                </p>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <p class="label">Billing Method</p>
                <p class="value" style="font-size: 13px; margin: 0;">Credit Card / Bank Transfer</p>
            </td>
        </tr>
    </table>
@endsection
