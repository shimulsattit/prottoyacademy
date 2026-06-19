<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->after('admin_id');
            $table->enum('teacher_status', ['pending', 'approved', 'rejected'])->nullable()->after('teacher_id');
            $table->text('teacher_rejection_reason')->nullable()->after('teacher_status');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['teacher_id', 'teacher_status', 'teacher_rejection_reason']);
        });
    }
};
