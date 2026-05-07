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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('job_category_id')->nullable();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('year_id')->nullable();
            $table->unsignedBigInteger('passage_id')->nullable();

            $table->enum('question_type', ['mcq', 'short_answer', 'long_answer', 'true_false', 'fill_in_the_blanks', 'matching'])->default('mcq');

            $table->integer('view')->default(0);
            $table->string('uuid');
            $table->text('question')->nullable();
            $table->string('slug')->unique();
            $table->text('correct_answer')->nullable();
            $table->enum('hard_level', ['easy', 'medium', 'hard', 'very_hard'])->default('easy');
            $table->integer('question_mark')->default(1);
            $table->text('content')->nullable();
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_article_tag')->nullable();
            $table->boolean('status')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('set null');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('set null');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('set null');
            $table->foreign('passage_id')->references('id')->on('passages')->onDelete('set null');

            $table->index(['category_id', 'job_category_id', 'exam_id'], 'questions_category_index');
            $table->index(['year_id', 'status'], 'questions_year_status_index');
            $table->index(['admin_id', 'uuid'], 'questions_admin_uuid_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
