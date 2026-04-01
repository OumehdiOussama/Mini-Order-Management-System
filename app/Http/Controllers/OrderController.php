<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validation
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'quantities' => 'required|array',
        ]);

        // Create order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'status' => 'pending',
        ]);

        // Attach products with quantity
        foreach ($request->products as $productId) {
            $quantity = $request->quantities[$productId] ?? 1;
            $order->products()->attach($productId, ['quantity' => $quantity]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
