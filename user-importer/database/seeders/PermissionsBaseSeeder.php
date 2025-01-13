<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsBaseSeeder extends Seeder
{
    public $roles;
    public $all = ['admin', 'user']; // all users
    public $admins = ['admin']; // admin roles

    public function __construct()
    {
        $this->roles = [
            'admin' => Role::where('name', 'admin')->first(),
            'user' => Role::where('name', 'user')->first(),
        ];
    }

    // Method to set permissions
    protected function setPermissions(array $roles, Permission $permission)
    {
        foreach ($this->roles as $key => $role) {
            if (! in_array($key, $roles)) {
                if ($role->hasPermission($permission->name)) {
                    $role->removePermission($permission->name);
                }
            } elseif (! $role->hasPermission($permission->name)) {
                $role->givePermission($permission->name);
            }
        }
    }
}
