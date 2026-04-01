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
        <h1>Customers List</h1>
        <a href="{{ route('customers.create') }}">Add Customer</a>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
            </tr>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>
                    <a href="{{ route('customers.edit', $customer->id) }}">Edit</a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline">
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