<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        // For simplicity, we'll assume user ID 3 is making the booking
        $user = User::find(3);
        if (!$user) {
            $this->command->warn('User with ID 3 not found.');
            return;
        }

        // Get 3 random carts from this user to attach to the booking
        $carts = Cart::where('user_id', $user->id)->inRandomOrder()->take(3)->get();

        $booking = Booking::create([
            'user_id'         => $user->id,
            'service_type'    => $faker->randomElement(['coach', 'clinic', 'breeder']),
            'provider_id'     => 5,
            'city_id'         => rand(1, 50),
            'appointment_date'=> Carbon::now()->addDays(rand(1, 10)),
            'booking_date'    => Carbon::now(),
            'status'          => $faker->randomElement(['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED']),
            'notes'           => $faker->sentence,
            'price'           => $carts->sum('price'),
            'tax'             => 0,
            'discount'        => 0,
            // 'total' can be calculated as needed (price + tax - discount)
            'payment_method'  => 'online',
            'payment_status'  => 'paid',
        ]);

        // Attach carts to the booking
        $booking->carts()->sync($carts->pluck('id')->toArray());
    }
}
