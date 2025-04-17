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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Insurance name
            $table->string('slug')->unique();                // URL-friendly unique slug
            $table->text('description')->nullable();         // Description of the insurance
            $table->string('logo')->nullable();              // Insurance company logo

            $table->string('contact_phone')->nullable();     // Contact phone number
            $table->string('contact_email')->nullable();     // Contact email address
            $table->string('website')->nullable();           // Official website URL

            $table->boolean('is_active')->default(true);     // Is the insurance currently active?
            $table->boolean('is_featured')->default(false);  // Is it a featured insurance?

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
