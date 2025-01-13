<?php

namespace App\Services;

use App\ImportStatus;
use App\Models\UserImport;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserImportService
{
    public function __construct(protected UserImport $model){}

    public function index()
    {
        return $this->model->all();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function processImport($file)
    {
        // Create a new UserImport record
        $this->store([
            'user_id' => User::first()->id,
            // 'user_id' => Auth::id(),
            'status' => ImportStatus::InProgress->value,
        ]);

        // Parse CSV data into collections
        $CSV = file_get_contents($file);
        $lines = explode(PHP_EOL, $CSV);
        $header = collect(str_getcsv(array_shift($lines)));
        $rows = collect($lines);
        $rows = $rows->filter(fn($row) => !empty($row));
        $data = $rows->map(fn($row) => $header->combine(str_getcsv($row)));

        dd($data);
    }


}
