<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a list of all orders with pagination.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);
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
        Gate::authorize('create', Order::class);

        $user = auth()->user();

        if ($user->role === 'customer') {
            $customers = $user->customer ? collect([$user->customer]) : collect();
        } else {
            // Only fetch columns needed for the dropdown — skips all heavy fields
            $customers = Customer::orderBy('name')
                ->select(['id', 'name', 'email'])
                ->get();
        }

        // Only show active products with stock, select only needed columns
        $products = Product::select(['id', 'name', 'price', 'stock', 'image_path'])
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $viewPath = $user->role === 'customer' ? 'customer.orders.create' : 'admin.orders.create';
        return view($viewPath, compact('customers', 'products'));
    }

    /*
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        Gate::authorize('create', Order::class);

        try {
            $this->orderService->createOrder($request->validated(), $request->user());
            return redirect()->route('orders.index')->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /*
     * Display order details with timeline and products.
     */
    public function show(Order $order)
    {
        Gate::authorize('view', $order);
        $order->load(['customer', 'products', 'timeline']);
        $viewPath = auth()->user()->role === 'customer' ? 'customer.orders.show' : 'admin.orders.show';
        return view($viewPath, compact('order'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        Gate::authorize('update', $order);
        $order->load(['customer', 'products']);
        $statuses = $order->availableTransitionStatuses();
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, Order $order)
    {
        Gate::authorize('update', $order);

        $this->orderService->updateOrderStatus($order, $request->validated());

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated successfully!');
    }

    /*
     * Delete the order and redirect to list.
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);
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
        Gate::authorize('downloadInvoice', $order);
        $order->load(['customer', 'products']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', compact('order'));
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
