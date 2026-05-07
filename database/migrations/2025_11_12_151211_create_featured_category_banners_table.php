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
        Schema::create('featured_category_banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('banner')->nullable();
            $table->text('alt_tag')->nullable();
            $table->text('content')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('featured_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_category_banners');
    }
};
