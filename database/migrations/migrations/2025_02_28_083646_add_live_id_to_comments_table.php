<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddLiveIdToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'live_id')) {
                $table->unsignedBigInteger('live_id')->nullable();
                // افزودن کلید خارجی به جدول `lives`
                $table->foreign('live_id')->references('id')->on('lives')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'live_id')) {
                // بررسی وجود کلید خارجی قبل از حذف
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'comments' AND COLUMN_NAME = 'live_id'");
                if (!empty($foreignKeys)) {
                    $table->dropForeign(['live_id']);
                }
                $table->dropColumn('live_id');
            }
        });
    }

}
