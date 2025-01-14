<?php

namespace App\Services;

use App\Models\User;

const PAGINATION = 10;

class UserService
{

    public function __construct(protected User $model){}

    public function store($data)
    {
        return $this->model->create($data)->addRole('user');
    }

    public function index()
    {
        return $this->model->paginate(PAGINATION);
    }

    private function getAllUsersInfo(): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get(['name', 'last_name', 'email']);
    }

    public function exportUsersCsvData(): string
    {
        $users = $this->getAllUsersInfo();

        $csvData = "name,lastname,email\n";

        foreach ($users as $user) {
            $csvData .= "{$user->name},{$user->last_name},{$user->email}\n";
        }

        return $csvData;
    }

}
