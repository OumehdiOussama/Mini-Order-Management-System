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
        <h1>Edit Product</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label>Name:</label><input type="text" name="name" value="{{ $product->name }}" required><br>
            <label>Price:</label><input type="number" name="price" step="0.01" value="{{ $product->price }}" required><br>
            <button type="submit">Update</button>
        </form>
    @endsection
</body>
</html>