<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(protected User $model){}

    public function store($data)
    {
        return $this->model->create($data)->addRole('user');
    }

}
