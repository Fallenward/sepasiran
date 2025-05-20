<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('live_id');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->string('type')->default('public'); // ✅ Ensure 'type' column exists
            $table->unsignedBigInteger('private_chat_user_id')->nullable(); // ✅ Private chat user
            $table->timestamps();

            // ✅ Foreign Keys
            $table->foreign('live_id')->references('id')->on('lives')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('private_chat_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
