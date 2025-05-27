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
        Schema::create('cart_transaction', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('cart_id')->nullable();
            // $table->unsignedBigInteger('transaction_id');
            // $table->timestamps();

            // $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            // $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');

            // $table->unique(['cart_id', 'transaction_id']); // prevent duplicate entries
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_transaction');
    }
};
