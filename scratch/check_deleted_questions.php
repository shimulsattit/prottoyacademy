<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$all = \App\Models\Question::withTrashed()
    ->where('job_category_id', 791)
    ->get();

echo "=== All Questions (including soft-deleted) under Job Category 791 ===\n";
echo "Count: " . count($all) . "\n";
foreach ($all as $q) {
    echo "ID: {$q->id}, Type: {$q->question_type}, Question: " . strip_tags($q->question) . ", Passage ID: {$q->passage_id}, Status: {$q->status}, Deleted At: {$q->deleted_at}, Created At: {$q->created_at}\n";
}
