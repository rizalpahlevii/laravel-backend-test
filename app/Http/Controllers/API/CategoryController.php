<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        $categories = CategoryResource::collection($categories);
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $input = $request->validated();
        $category = Category::create($input);
        return response()->json([
            'message' => 'Category created successfully',
            'category' => new CategoryResource($category)
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json(new CategoryResource($category));
        }
        return response()->json([
            'message' => 'Category not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();
        $category = Category::find($id);
        if ($category) {
            $category = tap($category)->update($input);
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => new CategoryResource($category)
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'Category not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully'
            ], ResponseAlias::HTTP_OK);
        }
        return response()->json([
            'message' => 'Category not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }
}
