<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private const CRUD_ACTIONS = ['create', 'read', 'update', 'delete'];
    private const RESOURCES = ['users', 'coaches', 'clinics', 'breeders', 'transactions', 'dashboard'];

    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::RESOURCES as $resource) {
                foreach (self::CRUD_ACTIONS as $action) {
                    $slug = "$resource.$action";
                    $name = "$resource.$action"; // same as slug

                    Permission::firstOrCreate(['name' => $name, 'slug' => $slug]);
                }
            }

            // Create roles
            $roles = ['admin', 'coach', 'clinic', 'breeder', 'user'];
            foreach ($roles as $role) {
                Role::firstOrCreate(['name' => $role]);
            }

            // Assign all permissions to admin role
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminRole->permissions()->sync(Permission::pluck('id'));
            }
        });
    }
}
