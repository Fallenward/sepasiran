<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedTinyInteger('value');
                $table->morphs('rateable');
                $table->timestamps();
            });
        }

        $now = now();

        $specialtySeeds = [
            37 => ['اطاعت‌پذیری', 'اصلاح رفتار', 'آموزش توله'],
            5 => ['نژادهای بزرگ', 'مشاوره خرید', 'نگهداری حرفه‌ای'],
            8 => ['معاینه عمومی', 'واکسیناسیون', 'جراحی سرپایی'],
        ];

        foreach ($specialtySeeds as $userId => $names) {
            if (! DB::table('users')->where('id', $userId)->exists()) {
                continue;
            }
            foreach ($names as $name) {
                $exists = DB::table('specialties')
                    ->where('user_id', $userId)
                    ->where('name', $name)
                    ->exists();
                if (! $exists) {
                    DB::table('specialties')->insert([
                        'user_id' => $userId,
                        'name' => $name,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        $ratingSeeds = [
            37 => [5, 5, 4, 5, 4],
            5 => [4, 5, 4],
            8 => [5, 4, 5, 5],
        ];

        foreach ($ratingSeeds as $providerId => $values) {
            if (! DB::table('users')->where('id', $providerId)->exists()) {
                continue;
            }
            $existing = DB::table('ratings')
                ->where('rateable_type', 'App\\Models\\User')
                ->where('rateable_id', $providerId)
                ->count();
            if ($existing > 0) {
                continue;
            }
            foreach ($values as $value) {
                DB::table('ratings')->insert([
                    'user_id' => null,
                    'value' => $value,
                    'rateable_type' => 'App\\Models\\User',
                    'rateable_id' => $providerId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};