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
        Schema::create('home_course_display_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id'); // دوره‌ای که قراره نمایش داده بشه
            $table->integer('order')->default(0);    // ترتیب نمایش
            $table->boolean('is_active')->default(true); // فعال یا غیرفعال
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_course_display_settings');
    }
};
