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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('job_category_id');
            $table->unsignedBigInteger('year_id');
            $table->string('uuid');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('name_in_bangla')->nullable();
            $table->text('content')->nullable();
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_article_tag')->nullable();
            $table->boolean('status')->default(false);
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');

            $table->index(['admin_id', 'job_category_id', 'year_id', 'uuid', 'slug', 'status']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
