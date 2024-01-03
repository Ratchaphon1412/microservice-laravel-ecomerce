<?php

namespace App\Infrastructure\Domain\Repositories;

use App\Infrastructure\Domain\Contracts\ProductRepository as ProductRepositoryContract;
use Recca0120\Repository\EloquentRepository;
use App\Models\Product;


class ProductRepository extends EloquentRepository implements ProductRepositoryContract
{
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
}