<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'dog_id')) {
                $table->foreignId('dog_id')->nullable()->after('user_id')->constrained('dogs')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'fulfillment_mode')) {
                $table->string('fulfillment_mode', 20)->default('direct')->after('service_type');
            }
            if (!Schema::hasColumn('bookings', 'preferred_date')) {
                $table->date('preferred_date')->nullable()->after('slot');
            }
            if (!Schema::hasColumn('bookings', 'preferred_slot')) {
                $table->string('preferred_slot', 10)->nullable()->after('preferred_date');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('PENDING','OPEN','ACCEPTED','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING'");
        }

        if (!Schema::hasTable('booking_providers')) {
            Schema::create('booking_providers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['booking_id', 'provider_id']);
            });
        }

        if (!Schema::hasTable('booking_offers')) {
            Schema::create('booking_offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('price', 10, 2);
                $table->text('message')->nullable();
                $table->date('proposed_date')->nullable();
                $table->string('proposed_slot', 10)->nullable();
                $table->string('status', 20)->default('pending');
                $table->timestamps();
                $table->index(['booking_id', 'provider_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_offers');
        Schema::dropIfExists('booking_providers');

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'preferred_slot')) {
                $table->dropColumn('preferred_slot');
            }
            if (Schema::hasColumn('bookings', 'preferred_date')) {
                $table->dropColumn('preferred_date');
            }
            if (Schema::hasColumn('bookings', 'fulfillment_mode')) {
                $table->dropColumn('fulfillment_mode');
            }
            if (Schema::hasColumn('bookings', 'dog_id')) {
                $table->dropConstrainedForeignId('dog_id');
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('PENDING','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING'");
        }
    }
};
