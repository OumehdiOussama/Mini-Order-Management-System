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
        \Illuminate\Support\Facades\Gate::authorize('viewAny', Order::class);
        $user = $request->user();
        $query = Order::with(['customer', 'products']);

        // Scope orders if user is a customer
        if ($user->role === 'customer') {
            if ($user->customer) {
                $query->where('customer_id', $user->customer->id);
            } else {
                $query->where('id', '<', 0); // No orders if no profile
            }
        }

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
        
        $viewPath = $user->role === 'customer' ? 'customer.orders.index' : 'admin.orders.index';
        return view($viewPath, compact('orders'));
    }

    /*
     * Show the form for creating a new resource.
     */
    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('create', Order::class);
        
        $user = auth()->user();
        if ($user->role === 'customer') {
            $customers = $user->customer ? collect([$user->customer]) : collect();
        } else {
            $customers = Customer::all();
        }

        $products = Product::all();
        $viewPath = $user->role === 'customer' ? 'customer.orders.create' : 'admin.orders.create';
        return view($viewPath, compact('customers', 'products'));
    }

    /*
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('create', Order::class);

        // Force customer_id if user is a customer
        if ($request->user()->role === 'customer') {
            if (!$request->user()->customer) {
                return redirect()->back()->with('error', 'You must have a customer profile to place orders.');
            }
            $request->merge(['customer_id' => $request->user()->customer->id]);
        }

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

        // Use a database transaction to ensure order and its related data are created atomically
        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
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

            return $order;
        });

        // Trigger Notifications outside transaction
        $adminsAndStaff = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
        \Illuminate\Support\Facades\Notification::send($adminsAndStaff, new \App\Notifications\NewOrderCreated($order));

        if ($order->customer && $order->customer->user) {
            $order->customer->user->notify(new \App\Notifications\OrderStatusUpdated($order));
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    /*
     * Display order details with timeline and products.
     */
    public function show(Order $order)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $order);
        $order->load(['customer', 'products', 'timeline']);
        $viewPath = auth()->user()->role === 'customer' ? 'customer.orders.show' : 'admin.orders.show';
        return view($viewPath, compact('order'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $order);
        $order->load(['customer', 'products']);
        $statuses = $order->availableTransitionStatuses();
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $order);
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

        $statusChanged = $order->status !== $request->status;

        // Update order
        $order->update([
            'status' => $request->status,
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
        ]);

        // Add timeline entry only when status actually changes
        if ($statusChanged) {
            $order->addTimeline(
                $request->status,
                $request->notes,
                $trackingNumber,
                $carrier
            );
        }

        // Trigger Notification
        if ($order->customer && $order->customer->user) {
            $order->customer->user->notify(new \App\Notifications\OrderStatusUpdated($order));
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated successfully!');
    }

    /*
     * Delete the order and redirect to list.
     */
    public function destroy(Order $order)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $order);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }

    /*
     * Export orders to Excel
     */
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OrdersExport, 'orders.xlsx');
    }

    /*
     * Download Order Invoice (PDF)
     */
    public function invoice(Order $order)
    {
        \Illuminate\Support\Facades\Gate::authorize('downloadInvoice', $order);
        $order->load(['customer', 'products']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', compact('order'));
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
