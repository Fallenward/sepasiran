<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dog_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('dog_orders', 'gateway_driver')) {
                $table->string('gateway_driver', 64)->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('dog_orders', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway_driver');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dog_orders', function (Blueprint $table) {
            if (Schema::hasColumn('dog_orders', 'gateway_transaction_id')) {
                $table->dropColumn('gateway_transaction_id');
            }
            if (Schema::hasColumn('dog_orders', 'gateway_driver')) {
                $table->dropColumn('gateway_driver');
            }
        });
    }
};