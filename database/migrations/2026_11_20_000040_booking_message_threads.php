<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('booking_messages', 'provider_id')) {
            Schema::table('booking_messages', function (Blueprint $table) {
                $table->foreignId('provider_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->index(['booking_id', 'provider_id']);
            });
        }

        // Backfill: prefer booking.provider_id, else offer provider, else sender if not customer.
        $rows = DB::table('booking_messages')->whereNull('provider_id')->get(['id', 'booking_id', 'user_id', 'booking_offer_id']);
        foreach ($rows as $row) {
            $booking = DB::table('bookings')->where('id', $row->booking_id)->first();
            $providerId = $booking->provider_id ?? null;
            if (! $providerId && $row->booking_offer_id) {
                $providerId = DB::table('booking_offers')->where('id', $row->booking_offer_id)->value('provider_id');
            }
            if (! $providerId && $booking && (int) $row->user_id !== (int) $booking->user_id) {
                $providerId = $row->user_id;
            }
            if ($providerId) {
                DB::table('booking_messages')->where('id', $row->id)->update(['provider_id' => $providerId]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('booking_messages', 'provider_id')) {
            Schema::table('booking_messages', function (Blueprint $table) {
                $table->dropConstrainedForeignId('provider_id');
            });
        }
    }
};