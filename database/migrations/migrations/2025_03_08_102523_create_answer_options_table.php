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
        Schema::create('answer_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('answer_sheet_id');
            $table->integer('question_number'); // شماره سوال
            $table->json('options'); // ذخیره گزینه‌ها به صورت JSON
            $table->integer('correct_option'); // گزینه صحیح (عدد بین 1 تا 4 برای تستی)
            $table->timestamps();

            $table->foreign('answer_sheet_id')->references('id')->on('answer_sheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_options');
    }
};
