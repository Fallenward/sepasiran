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
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('color')->nullable();
            $table->integer('age')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('cover_type')->nullable();
            $table->string('eye_color')->nullable();

            $table->boolean('shajare')->default(false);
            $table->unsignedBigInteger('breed_id')->nullable();

            $table->string('father_name')->nullable();
            $table->unsignedBigInteger('father_breed_id')->nullable();

            $table->string('mother_name')->nullable();
            $table->unsignedBigInteger('mother_breed_id')->nullable();

            $table->string('maternal_grandmother_name')->nullable();
            $table->unsignedBigInteger('maternal_grandmother_breed_id')->nullable();

            $table->string('maternal_grandfather_name')->nullable();
            $table->unsignedBigInteger('maternal_grandfather_breed_id')->nullable();

            $table->string('paternal_grandmother_name')->nullable();
            $table->unsignedBigInteger('paternal_grandmother_breed_id')->nullable();

            $table->string('paternal_grandfather_name')->nullable();
            $table->unsignedBigInteger('paternal_grandfather_breed_id')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dogs');
    }
};
