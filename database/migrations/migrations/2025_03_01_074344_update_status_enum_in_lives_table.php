<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('lives', function (Blueprint $table) {
            \DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('started', 'finished', 'pending', 'not_started') NOT NULL DEFAULT 'pending'");
        });
    }

    public function down()
    {
        Schema::table('lives', function (Blueprint $table) {
            \DB::statement("ALTER TABLE lives MODIFY COLUMN status ENUM('started', 'finished', 'pending') NOT NULL DEFAULT 'pending'");
        });
    }
};
