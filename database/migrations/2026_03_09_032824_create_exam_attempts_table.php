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
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('job_category_id');
            $table->unsignedBigInteger('student_id');
            $table->integer('total_questions')->default(0);
            $table->integer('answered')->default(0);
            $table->integer('right_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('no_answers')->default(0);
            $table->decimal('marks_obtained', 6,2)->default(0);
            $table->decimal('negative_marks', 6,2)->default(0);
            $table->boolean('passed')->default(false);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
