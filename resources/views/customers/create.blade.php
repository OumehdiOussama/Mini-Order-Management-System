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
        <h1>Add Customer</h1>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <label>Name:</label><input type="text" name="name" required><br>
            <label>Email:</label><input type="email" name="email" required><br>
            <label>Phone:</label><input type="text" name="phone" required><br>
            <button type="submit">Add</button>
        </form>
    @endsection
</body>
</html>