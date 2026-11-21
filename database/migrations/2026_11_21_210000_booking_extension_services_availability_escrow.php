<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('service_type', 32);
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            $table->unsignedBigInteger('base_price')->default(0);
            $table->string('currency', 8)->default('IRT');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->json('training_surcharges')->nullable();
            $table->json('extras')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'service_type', 'is_active']);
        });

        Schema::create('provider_availability_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('min_notice_minutes')->default(120);
            $table->unsignedInteger('max_advance_days')->default(60);
            $table->unsignedSmallInteger('slot_interval_minutes')->nullable();
            $table->timestamps();
        });

        Schema::create('provider_availability_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 0=Sunday .. 6=Saturday (Carbon)
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_break')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'weekday']);
        });

        Schema::create('provider_availability_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('all_day')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('kind', 32)->default('unavailable'); // unavailable|block
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'provider_service_id')) {
                $table->foreignId('provider_service_id')->nullable()->after('provider_id')->constrained('provider_services')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'settlement_status')) {
                $table->string('settlement_status', 32)->default('none')->after('payment_status');
            }
            if (! Schema::hasColumn('bookings', 'held_amount')) {
                $table->unsignedBigInteger('held_amount')->default(0)->after('settlement_status');
            }
        });

        // Backfill default services from provider_service_prices
        if (Schema::hasTable('provider_service_prices')) {
            $rows = DB::table('provider_service_prices')->get();
            foreach ($rows as $row) {
                $exists = DB::table('provider_services')
                    ->where('user_id', $row->user_id)
                    ->where('service_type', $row->service_type)
                    ->exists();
                if ($exists) {
                    continue;
                }
                DB::table('provider_services')->insert([
                    'user_id' => $row->user_id,
                    'service_type' => $row->service_type,
                    'name' => 'خدمت پیش‌فرض',
                    'description' => null,
                    'specialty_id' => null,
                    'base_price' => (int) $row->base_price,
                    'currency' => 'IRT',
                    'duration_minutes' => 60,
                    'training_surcharges' => $row->training_surcharges,
                    'extras' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'provider_service_id')) {
                $table->dropConstrainedForeignId('provider_service_id');
            }
            if (Schema::hasColumn('bookings', 'held_amount')) {
                $table->dropColumn('held_amount');
            }
            if (Schema::hasColumn('bookings', 'settlement_status')) {
                $table->dropColumn('settlement_status');
            }
        });

        Schema::dropIfExists('provider_availability_exceptions');
        Schema::dropIfExists('provider_availability_rules');
        Schema::dropIfExists('provider_availability_settings');
        Schema::dropIfExists('provider_services');
    }
};