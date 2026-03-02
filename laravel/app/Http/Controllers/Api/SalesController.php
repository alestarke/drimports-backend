<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateSaleRequest; 

class SalesController extends Controller
{
    public function index () {
        $data = Sale::with(['client', 'product'])->latest()->paginate(15);

        return response()->json($data);
    }

    public function store (CreateSaleRequest $request) {
        try {
            $data = $request->validated();

            $product = Product::findOrFail($data['product_id']);

            if ($product->stock_quantity < $data['quantity']) {
                return response()->json(['error' => true, 'message' => 'Insufficient stock for the requested product'], 422);
            }

            DB::beginTransaction();

            $data['total_price'] = $data['quantity'] * $data['unit_price'];

            $sale = Sale::create($data);

            $product->decrement('stock_quantity', $data['quantity']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'data' => $sale->load(['client', 'product'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => 'Failed to create sale'], 500);
        }
    }

    public function show (Sale $sale) {
        return response()->json($sale->load(['client', 'product']));
    }

    public function update (UpdateSaleRequest $request, Sale $sale) {
        try {
            $data = $request->validated();

            $sale->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Sale updated successfully',
                'data' => $sale->load(['client', 'product'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update sale'], 500);
        }
    }

    public function destroy (Sale $sale) {
        try {
            $sale->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sale deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to delete sale'], 500);
        }
    }
}
