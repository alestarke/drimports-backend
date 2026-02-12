<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;

class BrandsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Brand::class);

        $brands = Brand::whereNull('deleted_at')->get();

        return response()->json(['success' => true, 'message' => 'Brands fetched successfully.', 'data' => $brands]);
    }

    public function store(CreateBrandRequest $request)
    {
        $this->authorize('create', Brand::class);

        try {
            $data = $request->validated();

            $brand = Brand::create($data);

            return response()->json(['success' => true, 'message' => 'Brand created successfully', 'data' => $brand], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create brand: ' . $e->getMessage(), [
                'data' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'error_code' => 'BRAND_CREATION_FAILED', 'message' => 'Failed to create brand. Please try again later.'], 500);
        }
    }

    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);

        return response()->json(['success' => true, 'message' => 'Brand fetched successfully.', 'data' => $brand]);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        try {
            $data = $request->validated();

            $brand->update($data);

            return response()->json(['success' => true, 'message' => 'Brand updated successfully', 'data' => $brand], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update brand: ' . $e->getMessage(), [
                'brand_id' => $brand->id,
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'error_code' => 'BRAND_UPDATE_FAILED', 'message' => 'Failed to update brand. Please try again later.'], 500);
        }
    }

    public function destroy(Brand $brand)
    {
        $this->authorize('delete', $brand);
        
        try {
            $brand->delete();

            return response()->json(['success' => true, 'message' => 'Brand deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete brand: ' . $e->getMessage(), [
                'brand_id' => $brand->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'error_code' => 'BRAND_DELETE_FAILED',
                'message' => 'Failed to delete brand. Please try again later.'
            ], 500);
        }
    }           
}
