<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // payment_method remains online|offline; wallet/gateway preference is stored in gateway_driver.
    }

    public function down(): void
    {
        //
    }
};