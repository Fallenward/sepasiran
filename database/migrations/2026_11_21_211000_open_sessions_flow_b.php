<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 64)->nullable();
            $table->string('required_role', 32)->nullable();
            $table->string('required_specialty')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);
            $table->date('preferred_date')->nullable();
            $table->string('preferred_slot', 8)->nullable();
            $table->boolean('flexible_schedule')->default(true);
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->unsignedSmallInteger('participants_needed')->default(1);
            $table->unsignedSmallInteger('participants_filled')->default(0);
            $table->unsignedBigInteger('budget_min')->nullable();
            $table->unsignedBigInteger('budget_max')->nullable();
            $table->json('extras')->nullable();
            $table->json('images')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->string('status', 32)->default('DRAFT');
            $table->timestamps();
            $table->index(['status', 'category']);
        });

        Schema::create('open_session_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('open_session_id')->constrained('open_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dog_id')->nullable()->constrained('dogs')->nullOnDelete();
            $table->text('intro')->nullable();
            $table->text('experience')->nullable();
            $table->unsignedBigInteger('proposed_price')->nullable();
            $table->string('availability')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 32)->default('PENDING');
            $table->timestamps();
            $table->unique(['open_session_id', 'user_id']);
        });

        Schema::create('open_session_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('open_session_applications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('kind', 32)->default('proposal'); // proposal|counter
            $table->string('status', 32)->default('pending'); // pending|accepted|rejected
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('open_session_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('open_session_applications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 32)->default('text'); // text|proposal|counter|system
            $table->text('body')->nullable();
            $table->foreignId('offer_id')->nullable()->constrained('open_session_offers')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'open_session_id')) {
                $table->foreignId('open_session_id')->nullable()->after('provider_service_id')->constrained('open_sessions')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'open_session_application_id')) {
                $table->foreignId('open_session_application_id')->nullable()->after('open_session_id')->constrained('open_session_applications')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'open_session_application_id')) {
                $table->dropConstrainedForeignId('open_session_application_id');
            }
            if (Schema::hasColumn('bookings', 'open_session_id')) {
                $table->dropConstrainedForeignId('open_session_id');
            }
        });
        Schema::dropIfExists('open_session_messages');
        Schema::dropIfExists('open_session_offers');
        Schema::dropIfExists('open_session_applications');
        Schema::dropIfExists('open_sessions');
    }
};