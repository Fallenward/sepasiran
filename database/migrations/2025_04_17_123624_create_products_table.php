<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
        {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('slug')->unique();

                $table->decimal('price', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

                $table->unsignedInteger('stock')->default(0);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false);

                $table->string('sku')->nullable();
                $table->string('barcode')->nullable();

                $table->unsignedBigInteger('category_id')->nullable(); // You can add a FK for categories too if needed

                $table->unsignedBigInteger('brand_id')->nullable()->index();

                $table->string('thumbnail')->nullable();
                $table->json('images')->nullable();

                $table->string('buy_link')->nullable();

                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
