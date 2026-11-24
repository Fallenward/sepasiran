<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'coach', 'breeder', 'clinic', 'user'];

        foreach ($roles as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                ['name' => $roleName, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'family' => 'User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        DB::table('role_user')->updateOrInsert(
            ['user_id' => $admin->id, 'role_id' => $adminRoleId],
            ['user_id' => $admin->id, 'role_id' => $adminRoleId]
        );

        $roleUsers = [
            'coach' => 3,
            'breeder' => 3,
            'clinic' => 3,
            'user' => 5,
        ];

        $testUser = null;

        foreach ($roleUsers as $role => $count) {
            $roleId = DB::table('roles')->where('name', $role)->value('id');

            for ($i = 1; $i <= $count; $i++) {
                $user = User::updateOrCreate(
                    ['email' => "{$role}{$i}@example.com"],
                    [
                        'name' => ucfirst($role) . $i,
                        'family' => ucfirst($role) . ' Family',
                        'password' => Hash::make('password'),
                        'is_admin' => false,
                    ]
                );

                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $user->id, 'role_id' => $roleId],
                    ['user_id' => $user->id, 'role_id' => $roleId]
                );

                if ($role === 'coach' && $i === 1) {
                    $testUser = $user;
                }
            }
        }

        if ($testUser && DB::table('transactions')->where('user_id', $testUser->id)->doesntExist()) {
            DB::table('transactions')->insert([
                'user_id' => $testUser->id,
                'transaction_id' => (string) Str::uuid(),
                'cart_ids' => json_encode([13, 9]),
                'description' => 'پرداخت سفارش',
                'card_number' => '610433******1995',
                'ref_id' => 'RF1747517364804',
                'status' => 'pending',
                'amount' => 347000,
                'cart_price' => 807000,
                'tax' => 0,
                'discount' => 0,
                'payment_method' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
