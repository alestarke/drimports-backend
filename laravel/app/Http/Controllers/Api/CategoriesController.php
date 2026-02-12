<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::whereNull('deleted_at')->get();

        return response()->json(['success' => true, 'message' => 'Categories fetched successfully.', 'data' => $categories]);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return response()->json(['success' => true, 'message' => 'Category fetched successfully.', 'data' => $category]);
    }

    public function store(CreateCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        try {
            $data = $request->validated();

            $category = Category::create($data);

            return response()->json(['success' => true, 'message' => 'Category created successfully', 'data' => $category], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create category: ' . $e->getMessage(), [
                'data' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'error_code' => 'CATEGORY_CREATION_FAILED', 'message' => 'Failed to create category. Please try again later.'], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        try {
            $data = $request->validated();

            $category->update($data);

            return response()->json(['success' => true, 'message' => 'Category updated successfully', 'data' => $category], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update category: ' . $e->getMessage(), [
                'category_id' => $category->id,
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => true, 'error_code' => 'CATEGORY_UPDATE_FAILED', 'message' => 'Failed to update category. Please try again later.'], 500);
        }
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        try {
            $category->delete();

            return response()->json(['success' => true, 'message' => 'Category deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete category: ' . $e->getMessage(), [
                'category_id' => $category->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'error_code' => 'CATEGORY_DELETE_FAILED',
                'message' => 'Failed to delete category. Please try again later.'
            ], 500);
        }
    }
}
