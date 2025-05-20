<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quiz_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('correct_answers')->default(0);
            $table->integer('total_questions')->default(0);
            $table->decimal('score_percentage', 5, 2)->default(0.00);
            $table->timestamps();

            // جلوگیری از ورود اطلاعات تکراری برای یک کوییز و دانش‌آموز
            $table->unique(['quiz_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_scores');
    }
};
