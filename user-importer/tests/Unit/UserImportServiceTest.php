<?php

use App\Services\UserImportService;
use App\Models\UserImport;
use App\Models\User;
use App\ImportStatus;
use Illuminate\Support\Facades\Mail;
use App\Mail\ImportCompletedMail;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    $this->service = app(UserImportService::class);
});

it('can store a user import record', function () {

    $data = [
        'user_id' => User::factory()->create()->id,
        'status' => ImportStatus::InProgress->value,
    ];

    $service = new UserImportService(new UserImport());
    $result = $service->store($data);

    expect($result)->toBeInstanceOf(UserImport::class);
    expect($result->user_id)->toBe($data['user_id']);
    expect($result->status->value)->toBe($data['status']);
});

it('can process user import', function (User $user) {

    Mail::fake();

    // Authenticate as user
    $this->actingAs($user);

    // call seeder to create roles
    $this->seed('RoleSeeder');

    // create admin user
    $admin = User::factory()->create();
    $admin->addRole('admin');

    // create csv file with test data
    $csvPath = storage_path('test_files/test_users.csv');
    $csvContent = <<<CSV
        name,lastname,email
        John,Doe,john.doe@example.com
        Jane,Smith,jane.smith@example.com
        CSV;
    file_put_contents($csvPath, $csvContent);

    $service = new UserImportService(new UserImport());
    $service->processImport($csvPath);

    $userImport = UserImport::where('id', $user->id)->first();

    // expect user import to be created and completed
    expect($userImport->status->value)->toBe(ImportStatus::Completed->value);
    expect($userImport->user_id)->toBe($user->id);

    // expect users to be created
    $firstCreatedUser = User::where('email', 'john.doe@example.com')->first();
    $secondCreatedUser = User::where('email', 'jane.smith@example.com')->first();

    expect($firstCreatedUser)->not->toBeNull();
    expect($secondCreatedUser)->not->toBeNull();

    // expect mail to be sent to admin
    Mail::assertSent(ImportCompletedMail::class, function ($mail) use ($admin) {
        return $mail->hasTo($admin->email);
    });

    unlink($csvPath);

})->with('AuthUser');
