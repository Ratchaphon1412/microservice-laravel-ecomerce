<?php

namespace App\Infrastructure\Domain\Repository;

use Recca0120\Repository\EloquentRepository;
use App\Infrastructure\Domain\Contract\UserInterface;
use App\Models\User;


class UserRepository extends EloquentRepository implements UserInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
