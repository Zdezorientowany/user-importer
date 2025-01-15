<?php

use App\Models\User;
use App\Services\UserService;

beforeEach(function () {
    $this->service = new UserService(new User());
    // call role seeder
    $this->seed('RoleSeeder');
});

it('can store a user and assign the user role', function () {
    $data = [
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => bcrypt('password'),
    ];

    $result = $this->service->store($data);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->name)->toBe($data['name']);
    expect($result->hasRole('user'))->toBeTrue();
});

it('paginates users correctly', function () {
    User::factory(30)->create();

    $result = $this->service->index();

    expect($result->total())->toBe(30);
    expect($result->perPage())->toBe(10);
});

it('exports users data into CSV files', function () {
    User::factory(1000 + 5)->create();

    $filePaths = $this->service->exportUsersData();

    expect($filePaths)->toHaveLength(2);

    $firstFileContent = file_get_contents($filePaths[0]);
    $secondFileContent = file_get_contents($filePaths[1]);

    expect($firstFileContent)->toContain("name,lastname,email\n");
    expect($secondFileContent)->toContain("name,lastname,email\n");

    foreach ($filePaths as $filePath) {
        unlink($filePath);
    }
});

it('does not include admins in the export', function () {

    User::factory(10)->create();
    $admin = User::factory()->create();
    $admin->addRole('admin');

    $filePaths = $this->service->exportUsersData();
    $content = file_get_contents($filePaths[0]);

    expect($content)->not->toContain($admin->email);

    foreach ($filePaths as $filePath) {
        unlink($filePath);
    }
});
