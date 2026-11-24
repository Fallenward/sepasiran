<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_id')->constrained('insurances')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('duration_months');
            $table->unsignedBigInteger('price');
            $table->string('coverage_summary')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['insurance_id', 'is_active']);
        });

        Schema::create('insurance_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('insurance_id')->constrained('insurances')->cascadeOnDelete();
            $table->foreignId('insurance_plan_id')->constrained('insurance_plans')->cascadeOnDelete();
            $table->foreignId('dog_id')->nullable()->constrained('dogs')->nullOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status', 32)->default('active'); // active|expired|cancelled
            $table->unsignedBigInteger('amount_paid')->default(0);
            $table->string('payment_method', 32)->default('wallet');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['ends_at', 'status']);
        });

        // Seed default plans for existing active insurers.
        $insurers = DB::table('insurances')->where('is_active', 1)->pluck('id');
        $now = now();
        foreach ($insurers as $insuranceId) {
            $plans = [
                ['name' => '뿯ڽ뿯ڽ‌ماهه', 'duration_months' => 1, 'price' => 450_000, 'coverage_summary' => 'پوشش پا뿯ڽه درمان سرپا뿯ڽ뿯ڽ', 'sort_order' => 1],
                ['name' => 'سه‌ماهه', 'duration_months' => 3, 'price' => 1_150_000, 'coverage_summary' => 'پوشش درمان + اور뿯ڽانس', 'sort_order' => 2],
                ['name' => 'شش‌ماهه', 'duration_months' => 6, 'price' => 2_100_000, 'coverage_summary' => 'پوشش جامع ن뿯ڽم‌سال', 'sort_order' => 3],
                ['name' => '뿯ڽ뿯ڽ‌ساله', 'duration_months' => 12, 'price' => 3_800_000, 'coverage_summary' => 'پوشش 뿯ڽامل سالانه', 'sort_order' => 4],
            ];
            foreach ($plans as $plan) {
                DB::table('insurance_plans')->insert([
                    'insurance_id' => $insuranceId,
                    'name' => $plan['name'],
                    'duration_months' => $plan['duration_months'],
                    'price' => $plan['price'],
                    'coverage_summary' => $plan['coverage_summary'],
                    'is_active' => 1,
                    'sort_order' => $plan['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Ensure at least one demo insurer exists with Persian name if table was empty.
        if ($insurers->isEmpty()) {
            $insuranceId = DB::table('insurances')->insertGetId([
                'name' => 'ب뿯ڽمه سپاس‌پت',
                'slug' => 'sepas-pet-'.Str::lower(Str::random(4)),
                'description' => 'پوشش درمان، اور뿯ڽانس و خدمات 뿯ڽل뿯ڽن뿯ڽ뿯ڽ뿯ڽ برا뿯ڽ ح뿯ڽوانات خان뿯ڽ뿯ڽ.',
                'logo' => null,
                'contact_phone' => '02191000000',
                'contact_email' => 'insurance@sepasiran.test',
                'website' => null,
                'is_active' => 1,
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            foreach (
                [
                    ['name' => '뿯ڽ뿯ڽ‌ماهه', 'duration_months' => 1, 'price' => 450_000, 'coverage_summary' => 'پوشش پا뿯ڽه درمان سرپا뿯ڽ뿯ڽ', 'sort_order' => 1],
                    ['name' => 'سه‌ماهه', 'duration_months' => 3, 'price' => 1_150_000, 'coverage_summary' => 'پوشش درمان + اور뿯ڽانس', 'sort_order' => 2],
                    ['name' => 'شش‌ماهه', 'duration_months' => 6, 'price' => 2_100_000, 'coverage_summary' => 'پوشش جامع ن뿯ڽم‌سال', 'sort_order' => 3],
                    ['name' => '뿯ڽ뿯ڽ‌ساله', 'duration_months' => 12, 'price' => 3_800_000, 'coverage_summary' => 'پوشش 뿯ڽامل سالانه', 'sort_order' => 4],
                ] as $plan
            ) {
                DB::table('insurance_plans')->insert([
                    'insurance_id' => $insuranceId,
                    'name' => $plan['name'],
                    'duration_months' => $plan['duration_months'],
                    'price' => $plan['price'],
                    'coverage_summary' => $plan['coverage_summary'],
                    'is_active' => 1,
                    'sort_order' => $plan['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_licenses');
        Schema::dropIfExists('insurance_plans');
    }
};
