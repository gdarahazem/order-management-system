<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Services\Product\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected ProductService $productService;

    /**
     * Constructor
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * GET /api/products
     * List all products
     */
    public function index(): JsonResponse
    {
        try {
            $products = $this->productService->getAllProducts();

            return $this->successResponse(
                $products,
                'Products retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve products');
        }
    }

    /**
     * POST /api/products
     * Create a new product (uses StoreProductRequest for validation)
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return $this->successResponse(
                $product,
                'Product created successfully',
                201
            );

        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create product');
        }
    }
}
