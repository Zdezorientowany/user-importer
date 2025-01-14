<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}

    public function index(): \Inertia\Response
    {
        return Inertia::render('User/Index', [
            'users' => $this->service->index()
        ]);
    }

    public function userExport(): \Illuminate\Http\Response
    {
        $csvData = $this->service->exportUsersCsvData();

        $fileName = 'users_export.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

}
