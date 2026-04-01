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
        <h1>Edit Customer</h1>
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label>Name:</label><input type="text" name="name" value="{{ $customer->name }}" required><br>
            <label>Email:</label><input type="email" name="email" value="{{ $customer->email }}" required><br>
            <label>Phone:</label><input type="text" name="phone" value="{{ $customer->phone }}" required><br>
            <button type="submit">Update</button>
        </form>
    @endsection
</body>
</html>