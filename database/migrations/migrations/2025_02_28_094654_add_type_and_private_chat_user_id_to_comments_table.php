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
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('type', ['public', 'private'])->default('public');
            $table->unsignedBigInteger('private_chat_user_id')->nullable(); // To store the user involved in the private chat
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('private_chat_user_id');
        });
    }

};
