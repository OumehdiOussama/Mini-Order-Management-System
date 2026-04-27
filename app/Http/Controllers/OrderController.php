<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a list of all orders with pagination.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'products']);

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by Customer Name or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        return view('orders.index', compact('orders'));
    }

    /*
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /*
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|integer|distinct|exists:products,id',
            'quantities' => 'required|array',
        ]);

        // Validate each selected product has a valid quantity > 0
        foreach ($request->products as $productId) {
            if (!array_key_exists($productId, $request->quantities)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantities' => 'Each selected product must have a quantity']);
            }

            $quantity = $request->quantities[$productId] ?? 0;
            
            if (!is_numeric($quantity) || $quantity <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantities' => 'All ordered quantities must be greater than 0']);
            }
        }

        // Create order with pending status
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'status' => 'pending',
        ]);

        // Attach products with quantity
        foreach ($request->products as $productId) {
            $quantity = (int) $request->quantities[$productId];
            $order->products()->attach($productId, ['quantity' => $quantity]);
        }

        // Add initial timeline entry
        $order->addTimeline('pending', 'Order created');

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    /*
     * Display order details with timeline and products.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'products', 'timeline']);
        return view('orders.show', compact('order'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'products']);
        $statuses = $order->availableTransitionStatuses();
        return view('orders.edit', compact('order', 'statuses'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Tracking data is required when an order is moved to shipped.
        $requireTracking = $request->input('status') === 'shipped';

        $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES),
            'notes' => 'nullable|string',
            'tracking_number' => $requireTracking ? 'required|string|max:255' : 'nullable|string|max:255',
            'carrier' => $requireTracking ? 'required|string|max:255' : 'nullable|string|max:255',
        ], [
            'tracking_number.required' => 'Tracking number is required when shipping an order.',
            'carrier.required' => 'Carrier is required when shipping an order.',
        ]);

        if (!$order->canTransitionTo($request->status)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'status' => 'Invalid status transition from ' . ucfirst($order->status) . ' to ' . ucfirst($request->status) . '.',
                ]);
        }

        $trackingNumber = $request->status === 'shipped' ? $request->tracking_number : $order->tracking_number;
        $carrier = $request->status === 'shipped' ? $request->carrier : $order->carrier;

        // Update order
        $order->update([
            'status' => $request->status,
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
        ]);

        // Add timeline entry
        $order->addTimeline(
            $request->status,
            $request->notes,
            $trackingNumber,
            $carrier
        );

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated successfully!');
    }

    /*
     * Delete the order and redirect to list.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}
