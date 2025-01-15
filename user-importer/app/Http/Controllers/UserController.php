<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
use STS\ZipStream\Facades\Zip;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use STS\ZipStream\Builder as ZipStreamedResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}

    public function index(): \Inertia\Response
    {
        return Inertia::render('User/Index', [
            'users' => $this->service->index()
        ]);
    }

    public function userExport(): BinaryFileResponse|ZipStreamedResponse
    {
        $filePaths = $this->service->exportUsersData();

        if (count($filePaths) === 1) {
            // Single file
            $filePath = $filePaths[0];
            return Response::download($filePath)->deleteFileAfterSend();
        } else {
            // Multiple files as ZIP
            $zipFileName = 'users_export.zip';

            return  Zip::create($zipFileName, $filePaths);
        }
    }

}
