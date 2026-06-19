<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$questions = \App\Models\Question::where('category_id', 803)->get();
echo "=== Questions under Category ID 803 ===\n";
echo "Count: " . count($questions) . "\n";
foreach ($questions as $q) {
    echo "ID: {$q->id}, Type: {$q->question_type}, Question: " . strip_tags($q->question) . ", Job Category ID: {$q->job_category_id} (Name: " . ($q->job_category?->name ?? 'N/A') . "), Created At: {$q->created_at}\n";
}
