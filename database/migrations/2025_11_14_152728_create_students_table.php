<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('username', 50)->unique()->nullable();
            $table->string('email', 191)->unique()->nullable();
            $table->string('mobile', 20)->unique()->nullable();
            $table->string('password');
            $table->string('profile_photo_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            // $table->unsignedBigInteger('subscription_plan_id')->nullable()->index();
            $table->string('provider')->nullable();       // e.g., 'google', 'facebook'
            $table->string('provider_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();   // Soft delete support
        });

        // Foreign key (if you have subscription_plans table)
        // Schema::table('students', function (Blueprint $table) {
        //     $table->foreign('subscription_plan_id')
        //           ->references('id')
        //           ->on('subscription_plans')
        //           ->onDelete('set null');
        // });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // $table->dropForeign(['subscription_plan_id']);
        });
        Schema::dropIfExists('students');
    }
}
