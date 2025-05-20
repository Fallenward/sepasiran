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
        Schema::table('course_participants', function (Blueprint $table) {
            $table->string('spot_player_licence_id')->nullable();
            $table->string('spot_player_licence_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_participants', function (Blueprint $table) {
            $table->dropColumn(['spot_player_licence_id', 'spot_player_licence_url']);
        });
    }
};
