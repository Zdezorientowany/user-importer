<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserImport;
use App\ImportStatus;

class UserImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserImport::create([
            'user_id' => 1,
            'status' => ImportStatus::Completed,
        ]);
    }
}
