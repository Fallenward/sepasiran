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
            if (!Schema::hasColumn('bookings', 'booking_code')) {
                $table->string('booking_code', 32)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('bookings', 'breed_id')) {
                $table->unsignedBigInteger('breed_id')->nullable()->after('city_id');
            }
            if (!Schema::hasColumn('bookings', 'training_type')) {
                $table->string('training_type', 50)->nullable()->after('breed_id');
            }
            if (!Schema::hasColumn('bookings', 'slot')) {
                $table->string('slot', 10)->nullable()->after('appointment_date');
            }
            if (!Schema::hasColumn('bookings', 'contact_name')) {
                $table->string('contact_name', 120)->nullable()->after('notes');
            }
            if (!Schema::hasColumn('bookings', 'contact_phone')) {
                $table->string('contact_phone', 30)->nullable()->after('contact_name');
            }
        });

        $rows = DB::table('bookings')->whereNull('booking_code')->orderBy('id')->get(['id']);
        foreach ($rows as $row) {
            DB::table('bookings')->where('id', $row->id)->update([
                'booking_code' => 'SP-'.random_int(100000, 999999),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            foreach (['booking_code', 'breed_id', 'training_type', 'slot', 'contact_name', 'contact_phone'] as $col) {
                if (Schema::hasColumn('bookings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};