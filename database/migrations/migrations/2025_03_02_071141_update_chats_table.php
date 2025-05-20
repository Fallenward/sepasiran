<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            // ✅ Drop foreign key if it already exists
            $table->dropForeign(['private_chat_user_id']);

            // ✅ Ensure 'private_chat_user_id' column exists
            if (!Schema::hasColumn('chats', 'private_chat_user_id')) {
                $table->unsignedBigInteger('private_chat_user_id')->nullable()->after('type');
            }

            // ✅ Re-add foreign key constraint
            $table->foreign('private_chat_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['private_chat_user_id']);
            $table->dropColumn('private_chat_user_id');
        });
    }
};
