<?php

namespace Database\Seeders;

use App\Models\Permission;

class UserPermissionSeeder extends PermissionsBaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $read = Permission::create([
            'name' => 'users-read',
            'display_name' => 'Reading users',
            'description' => 'Can read users',
        ]);
        $this->setPermissions($this->admins, $read);

        $export = Permission::create([
            'name' => 'users-export',
            'display_name' => 'Exporting users',
            'description' => 'Can export users',
        ]);
        $this->setPermissions($this->admins, $export);
    }
}
