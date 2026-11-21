<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('course_enrollments')) {
            Schema::create('course_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->string('enrollment_code')->unique();
                $table->string('status', 32)->default('active');
                $table->unsignedBigInteger('amount_paid')->default(0);
                $table->string('payment_method', 32)->default('wallet');
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->timestamp('purchased_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'course_id']);
                $table->index(['user_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};