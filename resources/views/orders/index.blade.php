<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @extends('layouts.app')

    @section('content')
        <h1>Orders List</h1>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Total Price (MAD)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>
                        <ul>
                            @foreach($order->products as $product)
                                <li>{{ $product->name }} x {{ $product->pivot->quantity }} = {{ $product->price * $product->pivot->quantity }} MAD</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        {{ $order->products->sum(function($p){ return $p->price * $p->pivot->quantity; }) }}
                    </td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>
                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endsection
</body>
</html>