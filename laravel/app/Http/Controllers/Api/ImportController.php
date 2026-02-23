<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateImportRequest;
use App\Http\Requests\UpdateImportRequest;
use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Import::class);

        $imports = Import::with('product')->latest()->get();

        return response()->json($imports);
    }

    public function store(CreateImportRequest $request)
    {
        $this->authorize('create', Import::class);

        try {
            $data = $request->validated();

            $fees = $data['extra_fees_brl'] ?? 0;
            $totalUsd = $data['cost_price_usd'] * $data['quantity'];
            $baseBrl = $totalUsd * $data['exchange_rate'];
            $finalTotalBrl = $baseBrl + $fees;

            $data['extra_fees_brl'] = $fees;
            $data['total_cost_brl'] = $finalTotalBrl;

            $import = Import::create($data);

            $import->product()->increment('stock_quantity', $data['quantity']);

            return response()->json([
                'success' => true,
                'message' => 'Import created successfully',
                'data' => $import->load('product')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create import. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Import $import)
    {
        $this->authorize('view', $import);

        return response()->json([
            'success' => true,
            'message' => 'Import fetched successfully',
            'data' => $import->load('product')
        ]);
    }

    public function update(UpdateImportRequest $request, Import $import)
    {
        $this->authorize('update', $import);

        try {
            $data = $request->validated();

            $import->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Import updated successfully',
                'data' => $import->load('product')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update import. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Import $import)
    {
        try {
            $this->authorize('delete', $import);

            $import->delete();

            return response()->json([
                'success' => true,
                'message' => 'Import deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete import. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
