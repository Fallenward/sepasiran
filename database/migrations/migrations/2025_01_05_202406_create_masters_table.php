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
        Schema::create('masters', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Master's first name
            $table->string('family'); // Master's last name
            $table->string('slug'); // Master's slug
            $table->string('position'); // Master's position (e.g., دکتر, مهندس)
            $table->string('image')->nullable(); // Master's image (optional)
            $table->text('description')->nullable(); // Master's description (optional)
            $table->text('content')->nullable(); // Master's description (optional)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};
