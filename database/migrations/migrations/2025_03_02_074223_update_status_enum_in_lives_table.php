<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // ✅ تصحیح مقادیر نامعتبر قبل از تغییر ENUM
        DB::statement("UPDATE lives SET status = 'pending' WHERE status NOT IN ('started', 'finished', 'pending', 'not_started')");

        // ✅ تغییر نوع داده ENUM برای اضافه کردن 'not_started'
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('not_started', 'started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }

    public function down()
    {
        // ❗ قبل از تغییر ENUM، مقادیر 'not_started' را به مقدار معتبر 'pending' تغییر بده
        DB::statement("UPDATE lives SET status = 'pending' WHERE status = 'not_started'");

        // 🔄 حذف مقدار 'not_started' از ENUM
        Schema::table('lives', function (Blueprint $table) {
            DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }
};
