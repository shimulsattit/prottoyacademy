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
        Schema::create('student_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique();
            // General
            $table->tinyText('bio')->nullable();
            $table->string('cover_picture')->nullable();

            // Education
            $table->string('highest_education')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();

            // Professional
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->integer('years_of_experience')->nullable();

            // Address
            $table->string('address')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            // Social Media
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('personal_website_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();

            // Privacy Settings
            $table->boolean('show_email')->default(false);
            $table->boolean('show_mobile')->default(false);
            $table->boolean('show_education')->default(true);
            $table->boolean('show_professional')->default(true);
            $table->boolean('show_address')->default(false);
            $table->boolean('show_social_media')->default(true);

            // Notification Preferences
            $table->boolean('book_soft_copy_via_notification')->default(true);
            $table->boolean('book_soft_copy_via_email')->default(true);
            $table->boolean('book_soft_copy_via_sms')->default(true);
            $table->boolean('book_ordered_hard_copy_via_notification')->default(true);
            $table->boolean('book_ordered_hard_copy_via_email')->default(true);
            $table->boolean('book_ordered_hard_copy_via_sms')->default(true);
            $table->boolean('notify_course_updates')->default(true);
            $table->boolean('notify_new_courses')->default(true);
            $table->boolean('notify_promotions')->default(true);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_infos');
    }
};
