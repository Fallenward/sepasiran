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
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('photo')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();

            // Additional fields
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->decimal('wallet', 10, 2)->default(0);
            $table->unsignedBigInteger('level_id')->nullable();
            $table->unsignedBigInteger('field_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('grade_number')->default(0);

            // Timestamps
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
//            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
//            $table->foreign('city_id')->references('id')->on('iran_cities')->onDelete('set null');
        });

        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'admin', 'teacher', 'student'
            $table->timestamps();
        });

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'create-post', 'edit-post', 'delete-post'
            $table->string('slug')->unique(); // e.g., 'create.post', 'edit.post', 'delete.post'
            $table->text('description')->nullable(); // Optional description of the permission
            $table->timestamps();
        });

        // Create role_user pivot table
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'role_id']); // Composite primary key
        });

        // Create permission_role pivot table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']); // Composite primary key
        });

        // Create password_reset_tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('permission_role'); // Drop permission_role pivot table first
        Schema::dropIfExists('role_user'); // Drop role_user pivot table
        Schema::dropIfExists('permissions'); // Drop permissions table
        Schema::dropIfExists('roles'); // Drop roles table
        Schema::dropIfExists('users'); // Drop users table last
    }
};
