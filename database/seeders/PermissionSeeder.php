<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create super admin user
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = User::firstOrCreate(
            ['email' => 'nusagates@gmail.com'],
            ['name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'email_verified_at' => now()
            ]);
        $user->assignRole($role);

        //define all permissions
        $permissions = [
            "Create User", "Read User", "Update User", "Delete User",
            "Create Role", "Read Role", "Update Role", "Delete Role",
            "Read Permission", "Assign Permission"
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }
    }
}
