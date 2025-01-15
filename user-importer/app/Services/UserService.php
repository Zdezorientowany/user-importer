<?php

namespace App\Services;

use App\Models\User;

const PAGINATION = 10;
const MAX_USERS_PER_FILE = 1000;

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

    public function exportUsersData(): array
    {
        $users = $this->getAllUsersInfo();
        $chunks = $users->chunk(MAX_USERS_PER_FILE);
        $filePaths = [];

        // Ensure the storage/exports directory exists
        if (!file_exists(storage_path('exports'))) {
            mkdir(storage_path('exports'), 0755, true);
        }

        foreach ($chunks as $index => $chunk) {
            $csvData = "name,lastname,email\n";

            foreach ($chunk as $user) {
                $csvData .= "{$user->name},{$user->last_name},{$user->email}\n";
            }

            $fileName = $chunks->count() > 1
                ? "users_part_" . ($index + 1) . ".csv"
                : "users.csv";

            $filePath = storage_path("exports/{$fileName}");
            file_put_contents($filePath, $csvData);
            $filePaths[] = $filePath;
        }

        return $filePaths;
    }

}
