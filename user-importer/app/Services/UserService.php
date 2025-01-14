<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(protected User $model){}

    public function store($data)
    {
        return $this->model->create($data)->addRole('user');
    }

    public function index()
    {
        return $this->model->paginate(10);
    }

}
