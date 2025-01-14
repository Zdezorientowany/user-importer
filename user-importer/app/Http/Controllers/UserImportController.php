<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserImportService;
use Inertia\Inertia;
use App\Http\Requests\StoreUserImportRequest;

class UserImportController extends Controller
{
    public function __construct(protected UserImportService $service){}

    public function index()
    {
        return Inertia::render('UserImport/Index', [
            'imports' => $this->service->index()
        ]);
    }

    public function store(StoreUserImportRequest $request)
    {
        $file = $request->validated()['file'];

        $this->service->processImport($file);
    }


}
