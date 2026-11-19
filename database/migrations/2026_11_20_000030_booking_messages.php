<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('booking_messages')) {
            Schema::create('booking_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('booking_offer_id')->nullable()->constrained('booking_offers')->nullOnDelete();
                $table->text('body');
                $table->timestamps();
                $table->index(['booking_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_messages');
    }
};