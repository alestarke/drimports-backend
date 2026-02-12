<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with(['brand', 'category']);

        $perPage = $request->query('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $product->load(['brand', 'category']);

        return response()->json(['success' => true, 'message' => 'Product fetched successfully.', 'data' => $product]);
    }

    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', Product::class);

        try {
            $data = $request->validated();

            if (empty($data['slug'])) {
                $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            }

            $product = Product::create($data);

            $product->load(['brand', 'category']);

            return response()->json(['success' => true, 'message' => 'Product created successfully', 'data' => $product], 201);
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => true, 'error_code' => 'PRODUCT_CREATION_FAILED', 'message' => 'Product creation failed'], 500);
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        try {
            $data = $request->validated();

            if (empty($data['slug'])) {
                unset($data['slug']);
            }

            $product->update($data);

            $product->load(['brand', 'category']);

            return response()->json(['success' => true, 'message' => 'Product updated successfully', 'data' => $product], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update product: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'error_code' => 'PRODUCT_UPDATE_FAILED', 'message' => 'Failed to update product. Please try again later.'], 500);
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        try {
            $product->delete();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete product: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'error_code' => 'PRODUCT_DELETE_FAILED',
                'message' => 'Failed to delete product. Please try again later.'
            ], 500);
        }
    }
}
