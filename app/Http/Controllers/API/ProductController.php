<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\{CreateProductRequest, UpdateProductRequest};
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    /**
     * ImageUploadService instance
     *
     * @var ImageUploadService
     */
    private ImageUploadService $imageUploadService;

    /**
     * Controller constructor
     *
     * @param ImageUploadService $imageUploadService
     */
    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category', 'image')->get();
        $products = ProductResource::collection($products);
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        $input = $request->validated();
        $file = $request->file('image');

        $product = Product::create($input);

        $image = $this->imageUploadService->handle($file);
        $product->image()->create([
            'image_id' => $image->id,
        ]);

        $product->category()->create([
            'category_id' => $input['category_id'],
        ]);

        $product = $product->fresh('category', 'image');

        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product)
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with('category', 'image')->find($id);
        if ($product) {
            return response()->json(new ProductResource($product));
        }
        return response()->json([
            'message' => 'Product not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $input = $request->validated();
        $product = Product::find($id);
        if ($product) {
            $product = tap($product)->update($input);

            $productImage = ProductImage::whereProductId($product->id)->first();
            $image = $this->imageUploadService->handle($request->file('image'), $productImage->image);

            $product->image()->update([
                'image_id' => $image->id,
            ]);

            $product->category()->update([
                'category_id' => $input['category_id'],
            ]);

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => new ProductResource($product)
            ]);
        }
        return response()->json([
            'message' => 'Product not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully'
            ]);
        }
        return response()->json([
            'message' => 'Product not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }
}
