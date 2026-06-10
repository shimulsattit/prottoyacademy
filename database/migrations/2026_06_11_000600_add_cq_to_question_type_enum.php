<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE questions MODIFY COLUMN question_type ENUM('mcq', 'cq', 'short_answer', 'long_answer', 'true_false', 'fill_in_the_blanks', 'matching') NOT NULL DEFAULT 'mcq'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE questions MODIFY COLUMN question_type ENUM('mcq', 'short_answer', 'long_answer', 'true_false', 'fill_in_the_blanks', 'matching') NOT NULL DEFAULT 'mcq'");
    }
};
