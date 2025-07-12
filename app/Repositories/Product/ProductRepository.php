<?php
namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Get all products
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find product by ID
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create new product
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }


}
