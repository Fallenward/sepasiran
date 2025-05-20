<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');         // مسیر فایل
            $table->string('type');         // نوع فایل (image, video, pdf, etc.)
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }

};
