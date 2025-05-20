<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lives', function (Blueprint $table) {
            if (!Schema::hasColumn('lives', 'status')) {
                $table->enum('status', ['not_started', 'started'])->default('not_started');
            }
        });
    }

    public function down()
    {
        Schema::table('lives', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
