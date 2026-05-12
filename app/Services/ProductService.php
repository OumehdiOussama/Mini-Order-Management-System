<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Create a new product.
     */
    public function createProduct(array $data): Product
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_path'] = $data['image']->store('products', 'public');
        }
        unset($data['image']); // not a DB column

        return Product::create($data);
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteImage($product);
            $data['image_path'] = $data['image']->store('products', 'public');
        } elseif (isset($data['remove_image']) && $data['remove_image']) {
            $this->deleteImage($product);
            $data['image_path'] = null;
        }
        unset($data['image'], $data['remove_image']); // not DB columns

        $product->update($data);

        return $product;
    }

    /**
     * Delete a product and its image.
     */
    public function deleteProduct(Product $product): void
    {
        $this->deleteImage($product);
        $product->delete();
    }

    /**
     * Bulk delete products.
     */
    public function bulkDelete(array $ids): void
    {
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            $this->deleteImage($product);
        }
        Product::whereIn('id', $ids)->delete();
    }

    /**
     * Delete the product's image from storage.
     */
    protected function deleteImage(Product $product): void
    {
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
    }
}
