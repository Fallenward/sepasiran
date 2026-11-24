<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dog_colors')) {
            Schema::create('dog_colors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dog_eye_colors')) {
            Schema::create('dog_eye_colors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dog_cover_types')) {
            Schema::create('dog_cover_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dog_media')) {
            Schema::create('dog_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dog_id')->constrained('dogs')->cascadeOnDelete();
                $table->string('type', 20)->default('image');
                $table->string('path');
                $table->string('url')->nullable();
                $table->string('filename')->nullable();
                $table->string('mime')->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->string('caption')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_media');
        Schema::dropIfExists('dog_cover_types');
        Schema::dropIfExists('dog_eye_colors');
        Schema::dropIfExists('dog_colors');
    }
};