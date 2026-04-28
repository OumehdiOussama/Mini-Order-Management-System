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
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        
        return view('products.index', compact('products'));
    }

    /*
     * Show the form for creating a new resource.
    */
    public function create()
    {
        return view('products.create');
    }

    /*
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
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
        $product->load('orders');
        return view('products.show', compact('product'));
    }

    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
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
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }
}
