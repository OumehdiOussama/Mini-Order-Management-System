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
        <h1>Add Product</h1>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <label>Name:</label><input type="text" name="name" required><br>
            <label>Price:</label><input type="number" name="price" step="0.01" required><br>
            <button type="submit">Add</button>
        </form>
    @endsection
</body>
</html>