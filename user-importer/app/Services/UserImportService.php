<?php

namespace App\Services;

use App\ImportStatus;
use App\Models\UserImport;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Jobs\ProcessUserRowJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use App\Mail\ImportCompletedMail;

// Better to define this in config file or extend from a base service class but we will stick with this for now
const PAGINATION_COUNT = 10;

class UserImportService
{
    public function __construct(protected UserImport $model){}

    public function index()
    {
        return $this->model->paginate(PAGINATION_COUNT);
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    private function updateImportStatus($userImport, $status)
    {
        $userImport->update([
            'status' => $status,
        ]);
    }

    private function parseCsvFileToJobsArray($file, int $userImportId){

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $header = str_getcsv(array_shift($lines));

        $jobs = [];
        foreach ($lines as $line) {
            $row = array_combine($header, str_getcsv($line));
            $jobs[] = new ProcessUserRowJob($userImportId, $row);
        }

        return $jobs;
    }

    public function processImport($file)
    {
        // Create a new UserImport record
        $userImport = $this->store([
            // 'user_id' => User::first()->id, // For testing purposes
            'user_id' => Auth::id(),
            'status' => ImportStatus::InProgress->value,
        ]);

        $jobs = $this->parseCsvFileToJobsArray($file, $userImport->id);

        // Dispatch the batch
        Bus::batch($jobs)
            ->then(function () use ($userImport) {
                // Update status to completed
                $this->updateImportStatus($userImport, ImportStatus::Completed->value);

                activity()
                    ->performedOn($userImport->user)
                    ->causedBy($userImport->user)
                    ->log('User import completed');

                $admins = User::getAdmins();

                foreach ($admins as $admin) {
                    try {
                        Mail::to($admin->email)->send(new ImportCompletedMail($userImport));
                    } catch (\Exception $e) {
                        activity()
                            ->performedOn($userImport->user)
                            ->causedBy($userImport->user)
                            ->withProperty('error', $e->getMessage())
                            ->log('Failed to send import completed mail to ' . $admin->email);
                    }
                }

            })
            ->catch(function () use ($userImport) {
                // Update status to failed
                $this->updateImportStatus($userImport, ImportStatus::Failed->value);

                activity()
                    ->performedOn($userImport->user)
                    ->causedBy($userImport->user)
                    ->log('Batch failed during import');

            })
            ->dispatch();
    }


}
