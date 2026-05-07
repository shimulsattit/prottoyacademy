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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->string('uuid');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('name_in_bangla')->nullable();
            $table->text('header')->nullable();
            $table->text('header_in_bangla')->nullable();
            $table->text('content')->nullable();
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_article_tag')->nullable();
            $table->boolean('status')->default(false);
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->index(['parent_id', 'admin_Id', 'uuid', 'slug', 'status']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
