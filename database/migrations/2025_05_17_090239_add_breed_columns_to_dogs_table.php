<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreedColumnsToDogsTable extends Migration
{
    public function up()
    {
        Schema::table('dogs', function (Blueprint $table) {
            $table->unsignedBigInteger('breed_id')->nullable()->after('shajare');

            // Optional: add foreign keys if breeds are stored in another table (like categories)
            // $table->foreign('breed_id')->references('id')->on('categories')->nullOnDelete();
            // $table->foreign('father_breed_id')->references('id')->on('categories')->nullOnDelete();
            // $table->foreign('mother_breed_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('dogs', function (Blueprint $table) {
            // drop columns on rollback
            $table->dropColumn(['breed_id', 'father_breed_id', 'mother_breed_id']);
        });
    }
}
