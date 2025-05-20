<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // ✅ Ensure all existing status values are valid
        DB::statement("UPDATE lives SET status = 'pending' WHERE status NOT IN ('started', 'finished', 'pending')");

        // ✅ Modify ENUM column
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }

    public function down()
    {
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status VARCHAR(255) NOT NULL");
        });
    }
};
