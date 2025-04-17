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
            $table->enum('service_type', ['TRAINER', 'CLINIC', 'BREEDER']);
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('city_id');
            $table->dateTime('appointment_date');
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->text('notes')->nullable();

            $table->index('user_id');
            $table->index('provider_id');
            $table->index('city_id');
            $table->index('appointment_date');
            $table->timestamps();
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
