<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('service_type', ['coach', 'clinic', 'breeder', 'product', 'dog', 'insurance']);
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->dateTime('appointment_date')->nullable();
            $table->date('booking_date')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->nullable(); // Compute in app or update via model event
            $table->enum('payment_method', ['online', 'offline'])->default('online');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->unsignedBigInteger('transaction_id')->nullable();

            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');

            $table->index(['user_id', 'provider_id', 'appointment_date']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
