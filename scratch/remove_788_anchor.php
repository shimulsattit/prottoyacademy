<?php

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel kernel to access DB facade
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$targetString = 'id="788_anchor"';

echo "Searching and removing '$targetString' from all database tables...\n";

// Get all tables
$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE', 'prottoyacademy_main');
$keyName = "Tables_in_" . $dbName;

$updatedCount = 0;

foreach ($tables as $tableObj) {
    if (!isset($tableObj->$keyName)) {
        // Fallback for different key name formats
        $vars = get_object_vars($tableObj);
        $table = reset($vars);
    } else {
        $table = $tableObj->$keyName;
    }
    
    // Get columns for this table
    $columns = Schema::getColumnListing($table);
    
    foreach ($columns as $column) {
        try {
            // Find rows containing the target string
            $rows = DB::table($table)
                ->where($column, 'LIKE', '%' . $targetString . '%')
                ->get();
                
            if ($rows->count() > 0) {
                echo "Found in Table: $table | Column: $column\n";
                
                foreach ($rows as $row) {
                    $id = isset($row->id) ? $row->id : null;
                    
                    // Replace the string
                    $originalContent = $row->$column;
                    $updatedContent = str_replace($targetString, '', $originalContent);
                    
                    // Update in the database
                    if ($id !== null) {
                        DB::table($table)
                            ->where('id', $id)
                            ->update([$column => $updatedContent]);
                            
                        echo "  -> Successfully updated Row ID: $id (Removed '$targetString')\n";
                        $updatedCount++;
                    } else {
                        echo "  -> Found but could not update (No 'id' primary key column found)\n";
                    }
                }
            }
        } catch (\Exception $e) {
            // Skip non-textual columns or query errors
        }
    }
}

echo "--------------------------------------------------\n";
echo "Total rows updated: $updatedCount\n";
echo "Done.\n";
