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
    <h1>Create Order</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <!-- Select Customer -->
        <label>Customer:</label>
        <select name="customer_id" required>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>

        <hr>

        <!-- Select Products + Quantity -->
        <label>Products:</label>
        @foreach($products as $product)
            <div>
                <input type="checkbox" name="products[{{ $product->id }}]" value="{{ $product->id }}">
                {{ $product->name }} - {{ $product->price }} MAD
                Quantity: <input type="number" name="quantities[{{ $product->id }}]" value="1" min="1">
            </div>
        @endforeach

        <button type="submit">Create Order</button>
    </form>
    @endsection
</body>
</html>