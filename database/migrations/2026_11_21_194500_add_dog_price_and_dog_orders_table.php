<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dogs', function (Blueprint $table) {
            if (! Schema::hasColumn('dogs', 'price')) {
                $table->unsignedBigInteger('price')->nullable()->after('status');
            }
            if (! Schema::hasColumn('dogs', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
        });

        if (! Schema::hasTable('dog_orders')) {
            Schema::create('dog_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dog_id')->constrained('dogs')->cascadeOnDelete();
                $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('price');
                $table->string('status', 32)->default('pending');
                $table->string('payment_method', 32)->nullable();
                $table->string('transaction_ref')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['dog_id', 'status']);
                $table->index(['buyer_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_orders');
        Schema::table('dogs', function (Blueprint $table) {
            if (Schema::hasColumn('dogs', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('dogs', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};