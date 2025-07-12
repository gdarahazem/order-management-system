<?php

namespace App\Services\Product;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        try {
            return $this->productRepository->all();
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve products: ' . $e->getMessage());
        }
    }

    /**
     * Create new product
     */
    public function createProduct(array $data)
    {
        try {
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create product: ' . $e->getMessage());
        }
    }
}
