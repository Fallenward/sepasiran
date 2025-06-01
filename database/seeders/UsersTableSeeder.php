<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fa_IR');

        foreach (range(1, 10) as $i) {
            User::updateOrCreate(
                ['email' => "testuser$i@example.com"],
                [
                    'name' => $faker->firstName,
                    'family' => $faker->lastName,
                    'password' => Hash::make('password123'),
                    'is_admin' => false,
                    'bio' => $faker->realText(150),
                    'photo' => 'storage/users/user.svg',
                    'date_of_birth' => $faker->date('Y-m-d', '2005-01-01'),
                    'gender' => $faker->randomElement(['male', 'female']),
                    'status' => 'active',
                    'is_verified' => (bool)rand(0, 1),
                ]
            );
        }
    }
}
