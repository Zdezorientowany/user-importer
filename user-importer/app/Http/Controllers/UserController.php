<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}

    public function index()
    {
        return Inertia::render('User/Index', [
            'users' => $this->service->index()
        ]);
    }

}
