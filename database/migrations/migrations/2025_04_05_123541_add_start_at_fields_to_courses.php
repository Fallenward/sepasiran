<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // بررسی کنید که آیا ستون‌ها وجود دارند یا نه
            if (!Schema::hasColumn('courses', 'start_at_date')) {
                $table->string('start_at_date')->default(DB::raw('CURRENT_DATE'));
            }
            if (!Schema::hasColumn('courses', 'start_at_clock')) {
                $table->string('start_at_clock')->default(DB::raw('CURRENT_TIME'));
            }
        });

        // آپدیت کردن مقادیر موجود به تاریخ و ساعت جاری
        DB::table('courses')->update([
            'start_at_date' => now()->toDateString(), // تاریخ جاری
            'start_at_clock' => now()->toTimeString(), // ساعت جاری
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // حذف فیلدها در صورت برگرداندن مایگریشین
            $table->dropColumn('start_at_date');
            $table->dropColumn('start_at_clock');
        });
    }
};
