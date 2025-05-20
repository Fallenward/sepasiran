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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();;
            $table->string('status');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('field_id');

            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->float('rating', 2, 1)->default(0);

            $table->enum('payment_type', ['CREDIT', 'CASH','FREE'])->default('CASH');
            $table->integer('price');
            $table->integer('discount_price')->default(0);
            $table->boolean('register_from_site')->default(true);
            $table->boolean('show_registered_users_count')->default(true);
            $table->unsignedBigInteger('teacher_id');
            $table->string('start_at_date');
            $table->string('start_at_clock');
            $table->json('day_id');
            $table->string('video')->nullable();
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            $table->boolean('featured')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
