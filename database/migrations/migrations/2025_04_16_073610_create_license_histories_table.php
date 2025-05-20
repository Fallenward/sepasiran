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
        Schema::create('license_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_participant_id')->constrained()->onDelete('cascade');
            $table->string('old_license')->nullable();
            $table->string('new_license')->nullable();
            $table->timestamp('changed_at')->default(now());
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_histories');
    }
};
