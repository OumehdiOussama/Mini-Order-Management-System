<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; }
        .invoice-details, .customer-details { margin-bottom: 20px; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { width: 50%; vertical-align: top; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table.items th { background-color: #f8f9fa; }
        .totals { width: 100%; text-align: right; }
        .totals-table { display: inline-block; }
        .totals-table th, .totals-table td { padding: 5px 15px; }
        .badge { padding: 5px 10px; background-color: #e2e8f0; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Order #{{ $order->id }} - <span class="badge">{{ strtoupper($order->status) }}</span></p>
    </div>

    <table class="details-table">
        <tr>
            <td>
                <h3>Customer Information</h3>
                <p>
                    <strong>Name:</strong> {{ $order->customer->name }}<br>
                    <strong>Email:</strong> {{ $order->customer->email }}<br>
                    <strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}
                </p>
            </td>
            <td>
                <h3>Order Information</h3>
                <p>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}<br>
                    <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
                    @if($order->tracking_number)
                        <strong>Tracking:</strong> {{ $order->tracking_number }} ({{ $order->carrier }})
                    @endif
                </p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price (MAD)</th>
                <th>Quantity</th>
                <th>Total (MAD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ number_format($product->price * $product->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <th>Total Due:</th>
                <td><strong>{{ number_format($order->getTotalPrice(), 2) }} MAD</strong></td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center; color: #777; font-size: 12px;">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
