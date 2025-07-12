<?php

namespace App\Services\Order;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;
    protected ProductRepositoryInterface $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get all orders with their associated products and total price
     */
    public function getAllOrders()
    {
        try {
            $orders = $this->orderRepository->all();

            // Add product details to each order
            foreach ($orders as $order) {
                $order->products_details = $this->getProductsDetails($order->products);
            }

            return $orders;
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve orders: ' . $e->getMessage());
        }
    }

    /**
     * Create new order with calculated total price
     */
    public function createOrder(array $data)
    {
        try {
            $productIds = $data['products'];

            // Calculate total price from selected products
            $totalPrice = $this->calculateTotalPrice($productIds);

            $orderData = [
                'products' => $productIds,
                'total_price' => $totalPrice,
            ];

            $order = $this->orderRepository->create($orderData);

            // Add product details to response
            $order->products_details = $this->getProductsDetails($order->products);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Calculate total price from product IDs
     */
    private function calculateTotalPrice(array $productIds): float
    {
        $total = 0;

        foreach ($productIds as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $total += $product->price;
            }
        }

        return $total;
    }

    /**
     * Get product details for given product IDs
     */
    private function getProductsDetails(array $productIds): array
    {
        $products = [];

        foreach ($productIds as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }
}
