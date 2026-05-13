<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /*
     * Display a listing of all products.
    */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Product::class);
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Product::distinct()->pluck('category')->filter()->values();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /*
     * Show the form for creating a new resource.
    */
    public function create()
    {
        Gate::authorize('create', Product::class);
        return view('admin.products.create');
    }

    /*
     * Store a newly created resource in storage.
    */
    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create', Product::class);
        
        $this->productService->createProduct($request->validated());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /*
     * Display the product details.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);
        
        $orders = $product->orders()
            ->with('customer')
            ->latest()
            ->paginate(10);

        // Pre-calculate stats using database aggregates
        $totalUnits = $product->orders()->sum('order_product.quantity');
        $stats = [
            'total_orders'  => $product->orders()->count(),
            'total_units'   => $totalUnits,
            'total_revenue' => $product->price * $totalUnits,
            'delivered'     => $product->orders()->where('status', 'delivered')->count(),
        ];
            
        return view('admin.products.show', compact('product', 'orders', 'stats'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return view('admin.products.edit', compact('product'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);
        
        $this->productService->updateProduct($product, $request->validated());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /*
     * Delete the product from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $this->productService->deleteProduct($product);
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }

    /**
     * Delete multiple products from storage.
     */
    public function bulkDestroy(Request $request)
    {
        Gate::authorize('deleteAny', Product::class);
        
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $this->productService->bulkDelete($validated['ids']);

        return redirect()->route('products.index')->with('success', count($validated['ids']) . ' products deleted!');
    }
}
