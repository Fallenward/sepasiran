<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });

        // جایگزینی مقدار NULL با مقدار پیش‌فرض
        DB::statement("UPDATE questions SET title = 'سوال بدون عنوان' WHERE title IS NULL");

        Schema::table('questions', function (Blueprint $table) {
            $table->string('title')->default('سوال بدون عنوان')->change();
        });
    }

    public function down(): void
    {
        // جایگزینی مقدار NULL قبل از تغییر به NOT NULL
        DB::statement("UPDATE questions SET title = 'بدون عنوان' WHERE title IS NULL");

        Schema::table('questions', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
    }
};
