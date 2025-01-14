<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'User',
            'last_name' => 'Test',
            'email' => 'user@test.com',
            'password' => Hash::make('asd'),
        ])->addRole('user');

        User::factory()->create([
            'name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('asd'),
        ])->addRoles(['admin', 'user']);
    }
}
