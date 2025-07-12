<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function all();
    public function create(array $data);
}
