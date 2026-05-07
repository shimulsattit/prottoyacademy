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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('blog_author_id')->nullable()->constrained('blog_authors')->onDelete('set null');

            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->string('short_description')->nullable();
            $table->longText('content')->nullable();

            $table->string('thumbnail_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->boolean('status')->default(true)->comment('1=Published,0=Draft');

            // SEO fields
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('meta_google_schema')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Pivot Table for blog_tag relationship
        Schema::create('blog_blog_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('blog_tag_id')->constrained('blog_tags')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_blog_tag');
        Schema::dropIfExists('blogs');

    }
};
