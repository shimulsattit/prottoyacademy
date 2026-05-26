<?php

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel kernel to access DB facade
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$searchTerm = '788_anchor';

echo "Searching database for: '$searchTerm'...\n";

// Get all tables
$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE', 'prottoyacademy_main');
$keyName = "Tables_in_" . $dbName;

$found = false;

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
        // Query to check if the column contains the search term
        try {
            $results = DB::table($table)
                ->where($column, 'LIKE', '%' . $searchTerm . '%')
                ->get();
                
            if ($results->count() > 0) {
                $found = true;
                echo "--------------------------------------------------\n";
                echo "Found in Table: $table | Column: $column\n";
                foreach ($results as $row) {
                    $id = isset($row->id) ? $row->id : 'N/A';
                    echo "Row ID: $id\n";
                    echo "Content Snippet: " . substr(strip_tags(json_encode($row->$column)), 0, 200) . "...\n";
                }
            }
        } catch (\Exception $e) {
            // Skip non-textual columns or query errors
        }
    }
}

if (!$found) {
    echo "No occurrences of '$searchTerm' found in the database.\n";
}

echo "Done.\n";
