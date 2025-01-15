<?php

namespace Database\Seeders;

use App\Models\Permission;

class UserImportPermissionSeeder extends PermissionsBaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $read = Permission::create([
            'name' => 'users-import-read',
            'display_name' => 'Reading imports history',
            'description' => 'Can read imports history',
        ]);
        $this->setPermissions($this->admins, $read);

        $import = Permission::create([
            'name' => 'users-import',
            'display_name' => 'Importing users',
            'description' => 'Can import users',
        ]);
        $this->setPermissions($this->admins, $import);
    }
}
