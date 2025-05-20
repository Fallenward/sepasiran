<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ✅ ابتدا مقدارهای نامعتبر را اصلاح کن
        DB::statement("UPDATE lives SET status = 'pending' WHERE status NOT IN ('started', 'finished', 'pending')");

        // ✅ سپس ستون ENUM را تغییر بده
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }

    public function down()
    {
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('not_started', 'started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }
};
