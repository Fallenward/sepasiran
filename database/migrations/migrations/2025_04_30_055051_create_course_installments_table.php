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
        Schema::create('course_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('installment_number');       // شماره قسط
            $table->unsignedInteger('amount');                   // مبلغ قسط
            $table->date('due_date');                            // تاریخ سررسید
            $table->boolean('is_paid')->default(false);          // پرداخت شده یا نه
            $table->timestamp('paid_at')->nullable();            // زمان پرداخت
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_installments');
    }
};
