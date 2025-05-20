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
            $table->timestamp('license_expires_at')->nullable()->after('spot_player_licence');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_participants', function (Blueprint $table) {
            //
        });
    }
};
