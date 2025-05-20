<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // حذف course_id
            if (Schema::hasColumn('quizzes', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }

            // افزودن live_id
            $table->unsignedBigInteger('live_id')->after('id');
            $table->foreign('live_id')->references('id')->on('lives')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // برعکس کردن عملیات بالا
            $table->dropForeign(['live_id']);
            $table->dropColumn('live_id');

            $table->unsignedBigInteger('course_id')->nullable()->after('id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }
};
