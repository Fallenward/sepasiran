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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('color_setting_primary')->nullable();
            $table->string('color_setting_secondary')->nullable();
            $table->string('color_setting_third')->nullable();
            $table->string('color_setting_fourth')->nullable();
            $table->string('sms_setting_username')->nullable();
            $table->string('sms_setting_password')->nullable();
            $table->string('sms_setting_sender')->nullable();
            $table->string('sms_setting_apikey')->nullable();
            $table->string('footer_setting_enamad_logo')->nullable();
            $table->string('footer_setting_samandehi_logo')->nullable();
            $table->string('sms_setting_secretkey')->nullable();
            $table->json('footer_setting_useful_links')->nullable();
            $table->json('footer_setting_menu')->nullable();
            $table->text('footer_setting_about')->nullable();
            $table->string('footer_setting_logo')->nullable();
            $table->string('fav_icon')->nullable();
            $table->string('footer_setting_social_twitter')->nullable();
            $table->string('footer_setting_social_telegram')->nullable();
            $table->string('footer_setting_social_instagram')->nullable();
            $table->string('footer_setting_social_linkedin')->nullable();
            $table->text('about_setting_about')->nullable();
            $table->string('about_setting_img')->nullable();
            $table->enum('comment_settings_type', ['realtime', 'delayed'])->default('realtime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
