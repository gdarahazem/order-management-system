<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\Order\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected OrderService $orderService;

    /**
     * Constructor
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * GET /api/orders
     * List all orders with their associated products and total price
     */
    public function index(): JsonResponse
    {
        try {
            $orders = $this->orderService->getAllOrders();

            return $this->successResponse(
                $orders,
                'Orders retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve orders');
        }
    }

    /**
     * POST /api/orders
     * Create a new order with a list of product IDs
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return $this->successResponse(
                $order,
                'Order created successfully',
                201
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create order');
        }
    }
}
