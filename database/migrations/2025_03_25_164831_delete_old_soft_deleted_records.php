<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DeleteOldSoftDeletedRecords extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE EVENT delete_old_soft_deleted_records
            ON SCHEDULE EVERY 1 DAY
            DO
            BEGIN
                DELETE FROM categories WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
                DELETE FROM job_categories WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
                DELETE FROM questions WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
                DELETE FROM years WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
                DELETE FROM exams WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
                DELETE FROM passages WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 MONTH;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP EVENT IF EXISTS delete_old_soft_deleted_records");
    }
}