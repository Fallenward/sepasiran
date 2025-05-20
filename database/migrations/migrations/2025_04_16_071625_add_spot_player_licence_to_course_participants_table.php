<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_spot_player_licence_to_course_participants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpotPlayerLicenceToCourseParticipantsTable extends Migration
{
    public function up()
    {
        Schema::table('course_participants', function (Blueprint $table) {
            $table->string('spot_player_licence')->nullable()->after('course_id');
        });
    }

    public function down()
    {
        Schema::table('course_participants', function (Blueprint $table) {
            $table->dropColumn('spot_player_licence');
        });
    }
}
