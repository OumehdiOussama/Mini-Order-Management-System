<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a list of all customers with search functionality.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Customer::class);
        $query = Customer::query();
        
        // Search customers by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }
        
        $customers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Customer::class);
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Gate::authorize('create', Customer::class);
        
        $this->customerService->createCustomer($request->validated());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Display customer details with their orders.
     */
    public function show(Customer $customer)
    {
        Gate::authorize('view', $customer);
        
        $orders = $customer->orders()
            ->with('products')
            ->latest()
            ->paginate(10);

        // Pre-calculate stats using database aggregates for performance
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent'  => $customer->orders->sum(fn($o) => $o->getTotalPrice()), // This still uses collection, but we can't easily sum calculated total in SQL without raw queries or redundant columns.
            'delivered'    => $customer->orders()->where('status', 'delivered')->count(),
            'active'       => $customer->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
        ];
            
        return view('admin.customers.show', compact('customer', 'orders', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        Gate::authorize('update', $customer);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        Gate::authorize('update', $customer);
        
        $this->customerService->updateCustomer($customer, $request->validated());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    /**
     * Delete the customer and redirect to list.
     */
    public function destroy(Customer $customer)
    {
        Gate::authorize('delete', $customer);
        $this->customerService->deleteCustomer($customer);
        return redirect()->route('customers.index')->with('success', 'Customer deleted!');
    }

    /*
     * Export customers to Excel
     */
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomersExport, 'customers.xlsx');
    }
}
