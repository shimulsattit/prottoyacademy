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
        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_category_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('subject');
            $table->integer('time_minutes'); // Exam duration
            $table->integer('pass_mark'); // Min marks to pass
            $table->decimal('negative_mark', 4, 2)->default(0);
            $table->boolean('status')->default(1);
            $table->date('exam_date');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exams');
    }
};
