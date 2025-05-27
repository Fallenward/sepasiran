<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('booking_id')->nullable();
    $table->string('transaction_id')->unique();
    $table->text('description')->nullable();
    $table->string('card_number')->nullable();
    $table->string('ref_id')->nullable();
    $table->string('cart_ids')->nullable();
    $table->string('cart_price')->nullable();
    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
    $table->decimal('amount', 10, 2);
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('base_price', 10, 2)->default(0);

    $table->enum('payment_method', ['online', 'offline'])->default('online');
    $table->timestamps();


});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
