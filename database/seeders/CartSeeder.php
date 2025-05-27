<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // For simplicity, we'll get the first user to assign carts
        $user = User::first();
        if (!$user) {
            $this->command->warn('No user found. Please seed your users first.');
            return;
        }

        // Create 5 cart items for the user
        for ($i = 0; $i < 5; $i++) {
            Cart::create([
                'user_id'   => $user->id,
                'course_id' => rand(1, 10),
                'quantity'  => $faker->numberBetween(1, 3),
                'price'     => $faker->numberBetween(50000, 150000),
                'discount_id' => 0,
                'status'    => 'active',
            ]);
        }
    }
}
