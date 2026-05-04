<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
     * Display a listing of all products.
    */
    public function index(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('viewAny', Product::class);
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        
        return view('admin.products.index', compact('products'));
    }

    /*
     * Show the form for creating a new resource.
    */
    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('create', Product::class);
        return view('admin.products.create');
    }

    /*
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('create', Product::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /*
     * Display the product details.
     */
    public function show(Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $product);
        $product->load('orders');
        return view('admin.products.show', compact('product'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $product);
        return view('admin.products.edit', compact('product'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $product);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && \Storage::disk('public')->exists($product->image_path)) {
                \Storage::disk('public')->delete($product->image_path);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        } elseif ($request->boolean('remove_image')) {
            // Remove image if requested
            if ($product->image_path && \Storage::disk('public')->exists($product->image_path)) {
                \Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /*
     * Delete the product from storage.
     */
    public function destroy(Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }

    /**
     * Delete multiple products from storage.
     */
    public function bulkDestroy(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('deleteAny', Product::class);
        
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('products.index')->with('success', count($validated['ids']) . ' products deleted!');
    }
}
