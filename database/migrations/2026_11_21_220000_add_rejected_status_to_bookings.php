<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        // MySQL enum; SQLite/string columns already accept REJECTED.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('PENDING','OPEN','ACCEPTED','CONFIRMED','CANCELLED','COMPLETED','REJECTED') NOT NULL DEFAULT 'PENDING'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        DB::table('bookings')->where('status', 'REJECTED')->update(['status' => 'CANCELLED']);
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('PENDING','OPEN','ACCEPTED','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING'");
        }
    }
};
