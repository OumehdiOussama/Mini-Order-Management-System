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
        <h1>Products List</h1>
        <a href="{{ route('products.create') }}">Add Product</a>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th><th>Name</th><th>Price</th><th>Actions</th>
            </tr>
            @foreach($products as $product)
                <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }} MAD</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @endsection
</body>
</html>