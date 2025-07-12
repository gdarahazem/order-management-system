<?php
namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    protected Order $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
     * Get all orders
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create new order
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
