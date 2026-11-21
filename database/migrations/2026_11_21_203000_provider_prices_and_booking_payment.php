<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('service_type', 32);
            $table->unsignedBigInteger('base_price')->default(0);
            $table->json('training_surcharges')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'service_type']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'gateway_driver')) {
                $table->string('gateway_driver', 64)->nullable()->after('transaction_id');
            }
            if (! Schema::hasColumn('bookings', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway_driver');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY payment_method VARCHAR(32) NOT NULL DEFAULT 'gateway'");
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'gateway_transaction_id')) {
                $table->dropColumn('gateway_transaction_id');
            }
            if (Schema::hasColumn('bookings', 'gateway_driver')) {
                $table->dropColumn('gateway_driver');
            }
        });

        Schema::dropIfExists('provider_service_prices');
    }
};