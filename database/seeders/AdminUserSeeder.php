<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'coach', 'breeder', 'clinic', 'user'];

        // 1. Insert roles if they don't exist
        foreach ($roles as $roleName) {
            DB::table('roles')->updateOrInsert(['name' => $roleName]);
        }

        // 2. Create admin user
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

        DB::table('role_user')->updateOrInsert([
            'user_id' => $admin->id,
            'role_id' => $adminRoleId,
        ]);

        // 3. Create other users and assign roles
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

                DB::table('role_user')->updateOrInsert([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);

                if ($role === 'coach' && $i === 1) {
                    $testUser = $user;
                }
            }
        }

        // 4. Create test transaction for first coach
        if ($testUser) {
            DB::table('transactions')->insert([
                'user_id' => $testUser->id,
                'transaction_id' => Str::uuid(),
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
