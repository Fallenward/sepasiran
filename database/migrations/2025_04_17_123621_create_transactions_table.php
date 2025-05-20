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
    $table->string('transaction_id');
    $table->json('cart_ids')->nullable();
    $table->text('description')->nullable();
    $table->string('card_number')->nullable();
    $table->string('ref_id')->nullable();
    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
    $table->decimal('amount', 10, 2);
    $table->decimal('cart_price', 10, 2);
    $table->decimal('tax', 10, 2);
    $table->decimal('discount', 10, 2);
    $table->enum('payment_method',['online', 'offline'])->default('online');
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
