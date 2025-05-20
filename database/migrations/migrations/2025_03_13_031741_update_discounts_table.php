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
        Schema::table('discounts', function (Blueprint $table) {
//            $table->json('course_ids')->nullable()->default(json_encode([]))->change();
//            $table->json('user_ids')->nullable()->default(json_encode([]))->change();
//            $table->date('start_date')->nullable()->change();
//            $table->date('end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
//            $table->json('course_ids')->nullable(false)->change();
//            $table->json('user_ids')->nullable(false)->change();
//            $table->dateTime('start_date')->nullable(false)->change();
//            $table->dateTime('end_date')->nullable(false)->change();
        });
    }
};
