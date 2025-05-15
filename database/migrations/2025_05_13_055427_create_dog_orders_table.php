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
Schema::create('rs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('dog_id'); // Foreign key to dogs
    $table->unsignedBigInteger('buyer_id'); // User who is buying the dog
    $table->decimal('total_price', 10, 2); // Total price of the dog
    $table->string('status')->default('pending'); // pending, paid, open, etc.
    $table->timestamps();

    $table->foreign('dog_id')->references('id')->on('dogs')->onDelete('cascade');
    $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dog_orders');
    }
};
