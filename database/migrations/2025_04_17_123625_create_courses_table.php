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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // Course title
            $table->string('slug')->unique();                // Unique slug for URL
            $table->text('short_description')->nullable();   // Short description for previews
            $table->longText('description')->nullable();     // Full course description
            $table->string('thumbnail')->nullable();         // Course thumbnail image

            $table->unsignedBigInteger('instructor_id');     // Instructor user ID (foreign key)
            $table->unsignedBigInteger('category_id')->nullable(); // Course category ID
            $table->unsignedBigInteger('level_id')->nullable();    // Course difficulty level ID

            $table->decimal('price', 10, 2)->default(0);      // Base price
            $table->decimal('discount_price', 10, 2)->nullable(); // Discounted price

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Course status

            $table->boolean('is_featured')->default(false);   // Is course featured?
            $table->boolean('has_certificate')->default(false); // Does course offer certificate?

            $table->string('language')->default('fa');        // Course language (e.g., fa, en)

            $table->string('video_demo_url')->nullable();     // Demo video URL
            $table->integer('duration')->nullable();          // Course duration in minutes
            $table->integer('view_count')->default(0);        // Number of views

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
