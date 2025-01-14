<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserImport;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use Illuminate\Bus\Batchable;

class ProcessUserRowJob implements ShouldQueue
{
    use Queueable, Batchable;

    protected $userImportId;
    protected $row;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userImportId, array $row)
    {
        $this->userImportId = $userImportId;
        $this->row = $row;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $userImport = UserImport::find($this->userImportId);

        $validator = Validator::make($this->row, [
            'name' => 'required|string|alpha',
            'lastname' => 'required|string|alpha',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            activity()
                ->performedOn($userImport)
                ->causedBy($userImport->user)
                ->withProperties([
                    'row' => $this->row,
                    'errors' => $validator->errors()->toArray(),
                ])
                ->log('Failed to import user: ' . $validator->errors()->first());

            $userImport->increment('failed');
            return;
        }

        $userService = app(UserService::class);

        $userService->store([
            'name' => $this->row['name'],
            'last_name' => $this->row['lastname'],
            'email' => $this->row['email'],
            'password' => Hash::make('asd')
        ]);

        $userImport->increment('passed');
    }

}
