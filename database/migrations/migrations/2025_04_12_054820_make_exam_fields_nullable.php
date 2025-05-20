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
        Schema::table('exams', function (Blueprint $table) {
            $table->decimal('discount_price', 10, 2)->nullable()->change();
            $table->unsignedBigInteger('user_level')->nullable()->change();
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
            $table->integer('exam_counts')->nullable()->change();
            $table->string('plan')->nullable()->change();
            $table->boolean('first_exam_free')->nullable()->change();
            $table->boolean('buy_status')->nullable()->change();
        });
    }
};
