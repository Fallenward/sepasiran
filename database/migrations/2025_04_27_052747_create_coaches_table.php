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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id(); // به صورت پیش‌فرض 'bigIncrements'
            $table->bigInteger('city_id')->unsigned();  // اطمینان از یکسان بودن نوع داده‌ها
            $table->bigInteger('breed_id')->unsigned();
            $table->bigInteger('specialty_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('breed_id')->references('id')->on('breeds')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
