<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('option_number')->after('question_id')->nullable()->comment('شماره گزینه 1, 2, 3, 4');
        });
    }

    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('option_number');
        });
    }
};
